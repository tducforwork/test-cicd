<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\NotificationLog;
use App\Models\NotificationTemplate;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use App\Models\Product;
use App\Models\SellLog;
use App\Models\Province;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\FileTypeValidate;

class ManageUsersController extends Controller
{

    public function allUsers()
    {
        $pageTitle = __('All Customers');
        $users = $this->userData();
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function activeUsers()
    {
        $pageTitle = __('Active Users');
        $users = $this->userData('active');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function bannedUsers()
    {
        $pageTitle = __('Banned Customers');
        $users = $this->userData('banned');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function emailUnverifiedUsers()
    {
        $pageTitle = __('Email Unverified Customers');
        $users = $this->userData('emailUnverified');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function emailVerifiedUsers()
    {
        $pageTitle = __('Email Verified Customers');
        $users = $this->userData('emailVerified');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }


    public function mobileUnverifiedUsers()
    {
        $pageTitle = __('Mobile Unverified Customers');
        $users = $this->userData('mobileUnverified');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }


    public function mobileVerifiedUsers()
    {
        $pageTitle = __('Mobile Verified Customers');
        $users = $this->userData('mobileVerified');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }




    protected function userData($scope = null)
    {
        $users = User::query();
        if ($scope) {
            $users = $users->$scope();
        }

        $role = request()->role;
        if ($role == 'customer') {
            $users->notSeller();
        } elseif ($role == 'seller') {
            $users->seller();
        }

        return $users->with(['province'])->searchable(['username', 'email'])->orderBy('id', 'desc')->paginate(getPaginate());
    }

    public function detail($id)
    {
        $user               = User::findOrFail($id);
        $pageTitle          = $user->is_seller ? __('User & Seller Details') : __('Customer\'s Detail');

        // User Stats
        $totalShopping      = Order::paid()->where('user_id', $user->id)->sum('total_amount');
        $totalTransaction   = Transaction::where('user_id', $user->id)->count();
        $totalOrders        = Order::valid()->where('user_id', $user->id)->count();

        // Seller Stats
        $totalWithdraw    = 0;
        $sellerTransaction = 0;
        $totalProducts    = 0;
        $totalSold        = 0;

        if ($user->is_seller) {
            $totalWithdraw    = Withdrawal::where('seller_id', $user->id)->where('status', Status::PAYMENT_SUCCESS)->sum('amount');
            $sellerTransaction = Transaction::where('seller_id', $user->id)->count();
            $totalProducts    = Product::where('seller_id', $user->id)->count();
            $totalSold        = SellLog::where('seller_id', $user->id)->sum('product_price');
        }

        $countries          = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $provinces          = Province::orderBy('name')->get();
        return view('admin.users.detail', compact('pageTitle', 'user', 'totalShopping', 'totalTransaction', 'countries', 'totalOrders', 'totalWithdraw', 'sellerTransaction', 'totalProducts', 'totalSold', 'provinces'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'fullname'    => 'required|string|max:80',
            'email'       => 'required|email|string|max:40|unique:users,email,' . $user->id,
            'mobile'      => 'required|string|max:40',
            'province_id' => 'required|integer|exists:provinces,id',
            'ward_id'     => 'required|integer|exists:wards,id',
        ]);


        $user->mobile      = $request->mobile;

        $nameParts = explode(' ', $request->fullname, 2);
        $user->firstname = $nameParts[0];
        $user->lastname = isset($nameParts[1]) ? $nameParts[1] : '';

        $user->email       = $request->email;

        $user->address     = $request->address;
        $user->province_id = $request->province_id;
        $user->ward_id     = $request->ward_id;
        $user->dial_code   = '84'; // Default to Vietnam

        $user->ev = $request->ev ? Status::VERIFIED : Status::UNVERIFIED;
        $user->sv = $request->sv ? Status::VERIFIED : Status::UNVERIFIED;
        $user->ts = $request->ts ? Status::ENABLE : Status::DISABLE;
        $user->tv = $request->tv ? Status::VERIFIED : Status::UNVERIFIED;
        $user->kv = $request->kv ? Status::VERIFIED : Status::UNVERIFIED;

        if ($user->is_seller) {
            $user->id_card             = $request->id_card;
            $user->bank_account_number = $request->bank_account_number;
            $user->bank_name           = $request->bank_name;
            $user->bank_branch         = $request->bank_branch;
        }

        $user->save();

        $notify[] = ['success', __('Customer details updated successfully')];

        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'message' => __('Customer details updated successfully')]);
        }

        return back()->withNotify($notify);
    }

    public function login($id)
    {
        $user = User::findOrFail($id);
        $user->tv = $user->ts == Status::ENABLE ? Status::UNVERIFIED : Status::VERIFIED;
        $user->save();
        Auth::loginUsingId($id);
        return to_route('user.home');
    }

    public function status(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->status == Status::USER_ACTIVE) {
            $request->validate([
                'reason' => 'required|string|max:255'
            ]);
            $user->status = Status::USER_BAN;
            $user->ban_reason = $request->reason;

            if ($user->is_seller) {
                Product::where('seller_id', $user->id)->update(['status' => Status::DISABLE]);
            }

            $notify[] = ['success', __('Customer banned successfully')];
        } else {
            $user->status = Status::USER_ACTIVE;
            $user->ban_reason = null;

            if ($user->is_seller) {
                Product::where('seller_id', $user->id)->update(['status' => Status::ENABLE]);
            }

            $notify[] = ['success', __('Customer unbanned successfully')];
        }
        $user->save();
        return back()->withNotify($notify);
    }


    public function showNotificationSingleForm($id)
    {
        $user = User::findOrFail($id);
        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', __('Notification options are disabled currently')];
            return to_route('admin.users.detail', $user->id)->withNotify($notify);
        }
        $pageTitle = __('Send Notification to') . ' ' . $user->username;
        return view('admin.users.notification_single', compact('pageTitle', 'user'));
    }

    public function sendNotificationSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required',
            'via'     => 'required|in:email,sms,push',
            'subject' => 'required_if:via,email,push',
            'image'   => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', __('Notification options are disabled currently')];
            return to_route('admin.dashboard')->withNotify($notify);
        }

        $imageUrl = null;
        if ($request->via == 'push' && $request->hasFile('image')) {
            $imageUrl = fileUploader($request->image, getFilePath('push'));
        }

        $template = NotificationTemplate::where('act', 'DEFAULT')->where($request->via . '_status', Status::ENABLE)->exists();
        if (!$template) {
            $notify[] = ['warning', __('Default notification template is not enabled')];
            return back()->withNotify($notify);
        }

        $user = User::findOrFail($id);
        notify($user, 'DEFAULT', [
            'subject' => $request->subject,
            'message' => $request->message,
        ], [$request->via], pushImage: $imageUrl);
        $notify[] = ['success', __('Notification sent successfully')];
        return back()->withNotify($notify);
    }

    public function showNotificationAllForm()
    {
        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', __('Notification options are disabled currently')];
            return to_route('admin.dashboard')->withNotify($notify);
        }

        $notifyToUser = User::notifyToUser();
        $users        = User::active()->count();
        $pageTitle    = __('Notification to Verified Customers');

        if (session()->has('SEND_NOTIFICATION') && !request()->email_sent) {
            session()->forget('SEND_NOTIFICATION');
        }

        return view('admin.users.notification_all', compact('pageTitle', 'users', 'notifyToUser'));
    }

    public function sendNotificationAll(Request $request)
    {
        $request->validate([
            'via'                          => 'required|in:email,sms,push',
            'message'                      => 'required',
            'subject'                      => 'required_if:via,email,push',
            'start'                        => 'required|integer|gte:1',
            'batch'                        => 'required|integer|gte:1',
            'being_sent_to'                => 'required',
            'cooling_time'                 => 'required|integer|gte:1',
            'number_of_top_deposited_user' => 'required_if:being_sent_to,topDepositedUsers|integer|gte:0',
            'number_of_days'               => 'required_if:being_sent_to,notLoginUsers|integer|gte:0',
            'image'                        => ["nullable", 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ], [
            'number_of_days.required_if'               => __("Number of days field is required"),
            'number_of_top_deposited_user.required_if' => __("Number of top deposited user field is required"),
        ]);

        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', __('Notification options are disabled currently')];
            return to_route('admin.dashboard')->withNotify($notify);
        }


        $template = NotificationTemplate::where('act', 'DEFAULT')->where($request->via . '_status', Status::ENABLE)->exists();
        if (!$template) {
            $notify[] = ['warning', __('Default notification template is not enabled')];
            return back()->withNotify($notify);
        }

        if ($request->being_sent_to == 'selectedUsers') {
            if (session()->has("SEND_NOTIFICATION")) {
                $request->merge(['user' => session()->get('SEND_NOTIFICATION')['user']]);
            } else {
                if (!$request->user || !is_array($request->user) || empty($request->user)) {
                    $notify[] = ['error', __("Ensure that the customer field is populated when sending an email to the designated customer group")];
                    return back()->withNotify($notify);
                }
            }
        }

        $scope          = $request->being_sent_to;
        $userQuery      = User::oldest()->active()->$scope();

        if (session()->has("SEND_NOTIFICATION")) {
            $totalUserCount = session('SEND_NOTIFICATION')['total_user'];
        } else {
            $totalUserCount = (clone $userQuery)->count() - ($request->start - 1);
        }


        if ($totalUserCount <= 0) {
            $notify[] = ['error', __("Notification recipients were not found among the selected customer base.")];
            return back()->withNotify($notify);
        }


        $imageUrl = null;

        if ($request->via == 'push' && $request->hasFile('image')) {
            if (session()->has("SEND_NOTIFICATION")) {
                $request->merge(['image' => session()->get('SEND_NOTIFICATION')['image']]);
            }
            if ($request->hasFile("image")) {
                $imageUrl = fileUploader($request->image, getFilePath('push'));
            }
        }

        $users = (clone $userQuery)->skip($request->start - 1)->limit($request->batch)->get();

        foreach ($users as $user) {
            notify($user, 'DEFAULT', [
                'subject' => $request->subject,
                'message' => $request->message,
            ], [$request->via], pushImage: $imageUrl);
        }

        return $this->sessionForNotification($totalUserCount, $request);
    }


    private function sessionForNotification($totalUserCount, $request)
    {
        if (session()->has('SEND_NOTIFICATION')) {
            $sessionData                = session("SEND_NOTIFICATION");
            $sessionData['total_sent'] += $sessionData['batch'];
        } else {
            $sessionData               = $request->except('_token');
            $sessionData['total_sent'] = $request->batch;
            $sessionData['total_user'] = $totalUserCount;
        }

        $sessionData['start'] = $sessionData['total_sent'] + 1;

        if ($sessionData['total_sent'] >= $totalUserCount) {
            session()->forget("SEND_NOTIFICATION");
            $message = ucfirst($request->via) . " " . __("notifications were sent successfully");
            $url     = route("admin.users.notification.all");
        } else {
            session()->put('SEND_NOTIFICATION', $sessionData);
            $message = $sessionData['total_sent'] . " " . $sessionData['via'] . " " . __("notifications were sent successfully");
            $url     = route("admin.users.notification.all") . "?email_sent=yes";
        }
        $notify[] = ['success', $message];
        return redirect($url)->withNotify($notify);
    }

    public function countBySegment($methodName)
    {
        return User::active()->$methodName()->count();
    }

    public function list()
    {
        $query = User::active();

        if (request()->search) {
            $query->where(function ($q) {
                $q->where('email', 'like', '%' . request()->search . '%')->orWhere('username', 'like', '%' . request()->search . '%');
            });
        }
        $users = $query->orderBy('id', 'desc')->paginate(getPaginate());
        return response()->json([
            'success' => true,
            'users'   => $users,
            'more'    => $users->hasMorePages()
        ]);
    }

    public function notificationLog($id)
    {
        $user = User::findOrFail($id);
        $pageTitle = __('Notifications Sent to') . ' ' . $user->username;
        $logs = NotificationLog::where('user_id', $id)->with('user')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.reports.notifications.index', compact('pageTitle', 'logs', 'user'));
    }
}
