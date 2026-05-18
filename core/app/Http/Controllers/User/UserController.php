<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\GoogleAuthenticator;
use App\Models\DeviceToken;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Province;
use App\Models\Ward;

class UserController extends Controller
{
    public function home()
    {
        $pageTitle = 'Dashboard';
        $user = auth()->user();

        // Cơ chế self-healing: Nếu có đơn hàng thanh toán phí seller thành công nhưng chưa là seller
        if (!$user->is_seller) {
            $isPaid = Order::where('user_id', $user->id)
                ->where('remark', 'seller_registration_fee')
                ->where('payment_status', Status::PAYMENT_SUCCESS)
                ->exists();
            if ($isPaid) {
                $user->is_seller = Status::YES;
                $user->seller_active = Status::YES;
                $user->seller_activated_at = now();
                $user->save();
            }
        }

        $orders = Order::where('user_id', $user->id)->whereIn('payment_status', [1, 2])->get();
        return view('Template::user.dashboard', compact('pageTitle', 'orders'));
    }

    public function depositHistory(Request $request)
    {
        $pageTitle = 'Payment History';
        $deposits = auth()->user()->deposits()->searchable(['trx'])->with(['gateway'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view('Template::user.deposit_history', compact('pageTitle', 'deposits'));
    }

    public function show2faForm()
    {
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . gs('site_name'), $secret);
        $pageTitle = '2FA Security';
        return view('Template::user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user, $request->code, $request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts = Status::ENABLE;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $user = auth()->user();
        $response = verifyG2fa($user, $request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts = Status::DISABLE;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function userData()
    {
        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }

        $pageTitle  = 'User Data';
        $info       = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $provinces  = Province::orderBy('name')->get();

        return view('Template::user.user_data', compact('pageTitle', 'user', 'provinces', 'mobileCode'));
    }

    public function userDataSubmit(Request $request)
    {

        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }

        $countryData  = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes  = implode(',', array_column($countryData, 'dial_code'));
        $countries    = implode(',', array_column($countryData, 'country'));

        $request->validate([
            'name'          => 'required|string|max:80',
            'mobile'        => ['required', 'regex:/^([0-9]*)$/'],
            'province_id'   => 'required|integer|exists:provinces,id',
            'ward_id'       => 'required|integer|exists:wards,id',
            'address'       => 'required|string',
        ]);

        $nameParts = explode(' ', trim($request->name), 2);
        $user->firstname   = $nameParts[0];
        $user->lastname    = $nameParts[1] ?? '';
        $user->mobile      = $request->mobile;

        $user->address      = $request->address;
        $user->province_id  = $request->province_id;
        $user->ward_id      = $request->ward_id;
        $user->dial_code    = '84'; // Default to Vietnam

        $user->profile_complete = Status::YES;
        $user->save();

        $notify[] = ['success', 'Profile updated successfully'];
        return to_route('user.home')->withNotify($notify);
    }

    public function transactions()
    {
        $pageTitle = 'Transactions';
        $remarks = Transaction::distinct('remark')->orderBy('remark')->get('remark');

        $transactions = Transaction::where('user_id', auth()->id())->searchable(['trx'])->filter(['trx_type', 'remark'])->orderBy('id', 'desc')->paginate(getPaginate());

        return view('Template::user.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function addDeviceToken(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return ['success' => false, 'errors' => $validator->errors()->all()];
        }

        $deviceToken = DeviceToken::where('token', $request->token)->first();

        if ($deviceToken) {
            return ['success' => true, 'message' => 'Already exists'];
        }

        $deviceToken          = new DeviceToken();
        $deviceToken->user_id = auth()->id();
        $deviceToken->token   = $request->token;
        $deviceToken->is_app  = Status::NO;
        $deviceToken->save();

        return ['success' => true, 'message' => 'Token saved successfully'];
    }

    public function downloadAttachment($fileHash)
    {
        $filePath = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $title = slug(gs('site_name')) . '- attachments.' . $extension;
        try {
            $mimetype = mime_content_type($filePath);
        } catch (\Exception $e) {
            $notify[] = ['error', 'File does not exists'];
            return back()->withNotify($notify);
        }
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function becomeSeller()
    {
        $user = auth()->user();
        if ($user->is_seller) {
            return to_route('seller.home');
        }
        $pageTitle = 'Trở thành Người bán';
        return view('Template::user.become_seller', compact('pageTitle'));
    }

    public function becomeSellerSubmit(Request $request)
    {
        $user = auth()->user();
        if ($user->is_seller) {
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Bạn đã là người bán rồi!']);
            }
            return to_route('seller.home');
        }

        $request->validate([
            'address_seller' => 'required|string|max:255',
            'id_card' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:255',
            'bank_branch' => 'required|string|max:255',
        ], [
            'address_seller.required' => 'Vui lòng nhập địa chỉ kinh doanh.',
            'id_card.required' => 'Vui lòng nhập số CCCD.',
            'bank_name.required' => 'Vui lòng nhập tên ngân hàng.',
            'bank_account_number.required' => 'Vui lòng nhập số tài khoản.',
            'bank_branch.required' => 'Vui lòng nhập chi nhánh ngân hàng.',
        ]);

        $user->is_seller = Status::YES;
        $user->seller_active = Status::NO; // Chờ duyệt
        $user->address = $request->address_seller;
        $user->id_card = $request->id_card;
        $user->bank_name = $request->bank_name;
        $user->bank_account_number = $request->bank_account_number;
        $user->bank_branch = $request->bank_branch;
        $user->seller_activated_at = now();
        $user->save();

        // Gửi thêm thông báo Admin duyệt Người Bán nếu đăng ký làm Seller
        $adminNotificationSeller            = new \App\Models\AdminNotification();
        $adminNotificationSeller->user_id   = $user->id;
        $adminNotificationSeller->title     = 'Có yêu cầu đăng ký làm Người bán mới: ' . $user->fullname;
        $adminNotificationSeller->click_url = urlPath('admin.sellers.detail', $user->id);
        $adminNotificationSeller->save();

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Yêu cầu đăng ký làm Người bán đã gửi thành công! Đang chờ phê duyệt.'
            ]);
        }

        $notify[] = ['success', 'Yêu cầu đăng ký làm Người bán đã gửi thành công! Đang chờ phê duyệt.'];
        return to_route('user.home')->withNotify($notify);
    }

    public function getWards($id)
    {
        return Ward::active()->where('province_id', $id)->orderBy('name')->get();
    }
}
