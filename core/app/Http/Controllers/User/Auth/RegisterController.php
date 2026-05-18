<?php

namespace App\Http\Controllers\User\Auth;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\User;
use App\Models\UserLogin;
use App\Models\Cart;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{

    use RegistersUsers;

    public function showRegistrationForm()
    {
        $pageTitle = "Register";
        return view('Template::user.auth.register', compact('pageTitle'));
    }

    protected function validator(array $data)
    {

        $passwordValidation = Password::min(6);

        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $agree = 'nullable';
        if (gs('agree')) {
            $agree = 'required';
        }

        $validate     = Validator::make($data, [
            'fullName'  => 'required|string|max:255',
            'mobile'    => 'required|string|unique:users',
            'email'     => 'required|string|email|unique:users',
            'password'  => ['required', $passwordValidation],
            'captcha'   => 'sometimes|required',
            'agree'     => $agree,
            'address_seller' => 'nullable|string|max:255',
            'id_card'   => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
            'bank_branch' => 'nullable|string|max:255',
        ]);

        return $validate;
    }

    public function register(Request $request)
    {
        if (!gs('registration')) {
            $notify[] = ['error', 'Registration not allowed'];
            return back()->withNotify($notify);
        }
        $this->validator($request->all())->validate();

        $request->session()->regenerateToken();

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }



        event(new Registered($user = $this->create($request->all())));

        // LƯU Ý: Tuyệt đối không tự động đăng nhập khi tài khoản chưa xác thực Email.
        // Lưu email vào session tạm để phục vụ việc xác thực dưới dạng khách (Guest).
        session()->put('verify_email', $user->email);

        $notify[] = ['success', 'Đăng ký tài khoản thành công! Vui lòng nhập mã OTP gửi về Email để kích hoạt tài khoản.'];
        return to_route('user.verify.account')->withNotify($notify);
    }



    protected function create(array $data)
    {
        return \DB::transaction(function() use ($data) {
            //User Create
            $user            = new User();
            
            // Split fullName into firstname and lastname
            $nameParts = explode(' ', @$data['fullName'], 2);
            $user->firstname = $nameParts[0] ?? '';
            $user->lastname  = $nameParts[1] ?? '';
            
            $user->email     = strtolower($data['email']);
            $user->mobile    = @$data['mobile'];
            $user->password  = Hash::make($data['password']);
            
            if (!empty($data['is_seller'])) {
                $user->is_seller = Status::YES;
                $user->seller_active = Status::NO; // Seller needs admin approval
                $user->address = @$data['address_seller'];
                $user->id_card = @$data['id_card'];
                $user->bank_name = @$data['bank_name'];
                $user->bank_account_number = @$data['bank_account_number'];
                $user->bank_branch = @$data['bank_branch'];
            }
            
            // Luôn luôn bắt buộc xác thực Email qua mã OTP khi đăng ký tài khoản mới
            $user->ev = Status::NO;
            $user->ver_code = verificationCode(6);
            $user->ver_code_send_at = \Carbon\Carbon::now();
            
            $user->sv = gs('sv') ? Status::NO : Status::YES;
            $user->ts = Status::DISABLE;
            $user->tv = Status::ENABLE;
            $user->save();

            // Gửi mã OTP xác nhận trực tiếp về email đăng ký
            notify($user, 'EVER_CODE', [
                'code' => $user->ver_code
            ], ['email']);

            // Gửi thông báo đẩy về Admin Dashboard cho thành viên mới đăng ký
            $adminNotification            = new AdminNotification();
            $adminNotification->user_id   = $user->id;
            $adminNotification->title     = 'Thành viên mới đăng ký: ' . $user->fullname;
            $adminNotification->click_url = urlPath('admin.users.detail', $user->id);
            $adminNotification->save();

            // Gửi thêm thông báo Admin duyệt Người Bán nếu đăng ký làm Seller
            if ($user->is_seller == Status::YES) {
                $adminNotificationSeller            = new AdminNotification();
                $adminNotificationSeller->user_id   = $user->id;
                $adminNotificationSeller->title     = 'Có yêu cầu đăng ký làm Người bán mới: ' . $user->fullname;
                $adminNotificationSeller->click_url = urlPath('admin.sellers.detail', $user->id);
                $adminNotificationSeller->save();
            }


            //Login Log Create
            $ip        = getRealIP();
            $exist     = UserLogin::where('user_ip', $ip)->first();
            $userLogin = new UserLogin();

            if ($exist) {
                $userLogin->longitude    = $exist->longitude;
                $userLogin->latitude     = $exist->latitude;
                $userLogin->city         = $exist->city;
                $userLogin->country_code = $exist->country_code;
                $userLogin->country      = $exist->country;
            } else {
                $info                    = json_decode(json_encode(getIpInfo()), true);
                $userLogin->longitude    = @implode(',', $info['long']);
                $userLogin->latitude     = @implode(',', $info['lat']);
                $userLogin->city         = @implode(',', $info['city']);
                $userLogin->country_code = @implode(',', $info['code']);
                $userLogin->country      = @implode(',', $info['country']);
            }

            $userAgent          = osBrowser();
            $userLogin->user_id = $user->id;
            $userLogin->user_ip = $ip;

            $userLogin->browser = @$userAgent['browser'];
            $userLogin->os      = @$userAgent['os_platform'];
            $userLogin->save();

            return $user;
        });
    }

    public function checkUser(Request $request)
    {
        $exist['data'] = false;
        $exist['type'] = null;
        if ($request->email) {
            $exist['data'] = User::where('email', $request->email)->exists();
            $exist['type'] = 'email';
            $exist['field'] = 'Email';
        }
        if ($request->mobile) {
            $exist['data'] = User::where('mobile', $request->mobile)->where('dial_code', $request->mobile_code)->exists();
            $exist['type'] = 'mobile';
            $exist['field'] = 'Mobile';
        }
        if ($request->username) {
            $exist['data'] = User::where('username', $request->username)->exists();
            $exist['type'] = 'username';
            $exist['field'] = 'Username';
        }
        return response($exist);
    }

    public function registered()
    {
        return to_route('user.home');
    }

    public function verifyAccountForm(Request $request)
    {
        $email = session()->get('verify_email') ?: $request->email;
        if (!$email) {
            $notify[] = ['error', 'Không tìm thấy yêu cầu xác thực. Vui lòng đăng nhập hoặc đăng ký lại.'];
            return to_route('user.login')->withNotify($notify);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            $notify[] = ['error', 'Tài khoản không tồn tại.'];
            return to_route('user.login')->withNotify($notify);
        }

        if ($user->ev == Status::YES) {
            $notify[] = ['warning', 'Tài khoản của bạn đã được xác thực email. Vui lòng đăng nhập.'];
            return to_route('user.login')->withNotify($notify);
        }

        $pageTitle = "Xác thực Email";
        return view('Template::user.auth.authorization.email', compact('pageTitle', 'user', 'email'));
    }

    public function verifyAccountSubmit(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'email' => 'required|email'
        ], [
            'code.required' => 'Vui lòng nhập mã OTP xác thực.',
            'email.required' => 'Không tìm thấy email cần xác thực.'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $notify[] = ['error', 'Tài khoản không tồn tại.'];
            return to_route('user.login')->withNotify($notify);
        }

        if ($user->ev == Status::YES) {
            $notify[] = ['warning', 'Tài khoản của bạn đã được xác thực email. Vui lòng đăng nhập.'];
            return to_route('user.login')->withNotify($notify);
        }

        if ($user->ver_code != $request->code) {
            $notify[] = ['error', 'Mã xác thực OTP không chính xác. Vui lòng kiểm tra lại.'];
            return back()->withNotify($notify)->withInput();
        }

        // Kích hoạt tài khoản thành công
        $user->ev = Status::YES;
        $user->ver_code = null;
        $user->ver_code_send_at = null;
        $user->save();

        // Tiến hành đăng nhập chính thức cho người dùng
        $this->guard()->login($user);

        // Lưu thông tin giỏ hàng từ session (nếu có)
        if (session()->has('session_id')) {
            Cart::insertUserToCart($user->id, session('session_id'));
        }

        // Xóa email khỏi session verify tạm thời
        session()->forget('verify_email');

        $notify[] = ['success', 'Xác thực tài khoản thành công! Chào mừng bạn đến với Quảng Phát Mall.'];
        return to_route('user.home')->withNotify($notify);
    }

    public function verifyAccountResend(Request $request)
    {
        $email = session()->get('verify_email') ?: $request->email;
        if (!$email) {
            $notify[] = ['error', 'Không tìm thấy yêu cầu xác thực. Vui lòng thử lại.'];
            return to_route('user.login')->withNotify($notify);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            $notify[] = ['error', 'Tài khoản không tồn tại.'];
            return to_route('user.login')->withNotify($notify);
        }

        // Kiểm tra thời gian cooldown 2 phút
        $sendAt = $user->ver_code_send_at;
        if ($sendAt && \Carbon\Carbon::parse($sendAt)->addMinutes(2)->isFuture()) {
            $diff = \Carbon\Carbon::parse($sendAt)->addMinutes(2)->diffInSeconds(\Carbon\Carbon::now());
            $notify[] = ['error', "Vui lòng đợi {$diff} giây trước khi yêu cầu gửi lại mã xác thực mới."];
            return back()->withNotify($notify);
        }

        // Sinh mã mới và gửi lại
        $user->ver_code = verificationCode(6);
        $user->ver_code_send_at = \Carbon\Carbon::now();
        $user->save();

        notify($user, 'EVER_CODE', [
            'code' => $user->ver_code
        ], ['email']);

        $notify[] = ['success', 'Mã OTP xác thực mới đã được gửi về email của bạn.'];
        return back()->withNotify($notify);
    }
}
