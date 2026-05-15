<?php

namespace App\Http\Controllers\Seller\Auth;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    use RegistersUsers;


    public function showRegistrationForm()
    {
        $pageTitle = "Sign Up as Seller";
        return view('Template::seller.auth.register', compact('pageTitle'));
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

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    protected function guard()
    {
        return Auth::guard('web');
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
            'firstname' => 'required|string|max:40',
            'lastname'  => 'required|string|max:40',
            'id_card'   => 'required|string|max:40',
            'bank_account_number' => 'required|string|max:40',
            'bank_name'   => 'required|string|max:255',
            'bank_branch' => 'required|string|max:255',
            'email'     => 'required|string|email|unique:users',
            'password'  => ['required', $passwordValidation],
            'captcha'   => 'sometimes|required',
            'agree'     => $agree
        ]);

        return $validate;
    }


    protected function create(array $data)
    {

        $seller = new User();
        $seller->firstname    = @$data['firstname'];
        $seller->lastname     = @$data['lastname'];
        $seller->email        = strtolower(trim($data['email']));
        $seller->password     = Hash::make($data['password']);
        
        $seller->id_card             = $data['id_card'];
        $seller->bank_account_number = $data['bank_account_number'];
        $seller->bank_name           = $data['bank_name'];
        $seller->bank_branch         = $data['bank_branch'];

        $seller->is_seller = 1;
        $seller->seller_active = 0; // Pending approval

        $seller->status = Status::USER_ACTIVE;
        $seller->ev = gs('ev') ? Status::NO : Status::YES;
        $seller->sv = gs('sv') ? Status::NO : Status::YES;
        $seller->ts = Status::DISABLE;
        $seller->tv = Status::ENABLE;
        $seller->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $seller->id;
        $adminNotification->title = 'New seller registered';
        $adminNotification->click_url = urlPath('admin.sellers.detail', $seller->id);
        $adminNotification->save();

        //Login Log Create
        $ip = $_SERVER["REMOTE_ADDR"];
        $exist = UserLogin::where('user_ip', $ip)->first();
        $sellerLogin = new UserLogin();

        //Check exist or not
        if ($exist) {
            $sellerLogin->longitude =  $exist->longitude;
            $sellerLogin->latitude =  $exist->latitude;
            $sellerLogin->city =  $exist->city;
            $sellerLogin->country_code = $exist->country_code;
            $sellerLogin->country =  $exist->country;
        } else {
            $info = json_decode(json_encode(getIpInfo()), true);
            $sellerLogin->longitude =  @implode(',', $info['long']);
            $sellerLogin->latitude =  @implode(',', $info['lat']);
            $sellerLogin->city =  @implode(',', $info['city']);
            $sellerLogin->country_code = @implode(',', $info['code']);
            $sellerLogin->country =  @implode(',', $info['country']);
        }

        $userAgent = osBrowser();
        $sellerLogin->user_id = $seller->id;
        $sellerLogin->user_ip =  $ip;

        $sellerLogin->browser = @$userAgent['browser'];
        $sellerLogin->os = @$userAgent['os_platform'];
        $sellerLogin->save();


        return $seller;
    }

    public function checkSeller(Request $request)
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

    public function registered($request, $user)
    {
        Auth::guard('web')->logout();
        $notify[] = ['success', 'Your registration is successful. Please wait for admin approval.'];
        return redirect()->route('seller.login')->withNotify($notify);
    }
}
