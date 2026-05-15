<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Models\Shop;
use App\Models\User;
use App\Models\Product;
use App\Models\SellLog;
use App\Models\Withdrawal;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\FileTypeValidate;
use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use App\Models\NotificationTemplate;
use App\Models\Category;

class ManageSellersController extends Controller
{
    public function allSellers()
    {
        $pageTitle = __('All Sellers');
        $sellers   = $this->sellerData();
        return view('admin.seller.list', compact('pageTitle', 'sellers'));
    }

    public function activeSellers()
    {
        $pageTitle = __('Active Sellers');
        $sellers   = $this->sellerData('active');
        return view('admin.seller.list', compact('pageTitle', 'sellers'));
    }

    public function bannedSellers()
    {
        $pageTitle = __('Banned Sellers');
        $sellers   = $this->sellerData('banned');
        return view('admin.seller.list', compact('pageTitle', 'sellers'));
    }

    public function pendingSellers()
    {
        $pageTitle = __('Pending Sellers');
        $sellers   = $this->sellerData('sellerPending');
        return view('admin.seller.list', compact('pageTitle', 'sellers'));
    }

    public function emailVerifiedSellers()
    {
        $pageTitle = __('Email Verified Seller');
        $sellers   = $this->sellerData('emailVerified');
        return view('admin.seller.list', compact('pageTitle', 'sellers'));
    }

    public function emailUnverifiedSellers()
    {
        $pageTitle = __('Email Unverified Seller');
        $sellers   = $this->sellerData('emailUnverified');
        return view('admin.seller.list', compact('pageTitle', 'sellers'));
    }

    public function mobileUnverifiedSellers()
    {
        $pageTitle = __('Mobile Unverified Seller');
        $sellers   = $this->sellerData('mobileUnverified');
        return view('admin.seller.list', compact('pageTitle', 'sellers'));
    }

    public function mobileVerifiedSellers()
    {
        $pageTitle = __('Mobile Verified Seller');
        $sellers   = $this->sellerData('mobileVerified');
        return view('admin.seller.list', compact('pageTitle', 'sellers'));
    }



    private function sellerData($scope = null)
    {
        $sellers = User::seller();
        if ($scope) {
            $sellers = $sellers->$scope();
        }
        return $sellers->searchable(['username', 'email'])->orderBy('id', 'desc')->paginate(getPaginate());
    }

    public function detail($id)
    {
        return redirect()->route('admin.users.detail', $id);
    }

    public function sellLogs($id)
    {
        $seller = User::findOrFail($id);
        $pageTitle = __("Sell logs of") . " : $seller->username";
        $logs = SellLog::where('seller_id', $seller->id)->paginate(getPaginate());
        return view('admin.seller.sales_log', compact('pageTitle', 'logs', 'seller'));
    }

    public function sellerProducts($id)
    {
        $seller     = User::findOrFail($id);
        $pageTitle  = __("Products of") . " : $seller->username";
        
        $query = Product::where('seller_id', $seller->id);

        if (request()->category_id) {
            $query->whereHas('categories', function($q) {
                $q->where('category_id', request()->category_id);
            });
        }

        $products   = $query->searchable(['name'])->orderBy('id', 'desc')->paginate(getPaginate());
        $categories = Category::with('allSubcategories')->where('parent_id', null)->get();
        return view('admin.products.index', compact('pageTitle', 'products', 'seller', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $seller = User::findOrFail($id);

        $request->validate([
            'firstname'   => 'required|max:40',
            'lastname'    => 'required|max:40',
            'email'       => 'required|email|max:191|unique:users,email,' . $seller->id,
            'mobile'      => 'required|max:40|unique:users,mobile,' . $seller->id,
            'province_id' => 'required|integer|exists:provinces,id',
            'ward_id'     => 'required|integer|exists:wards,id',
        ]);

        $seller->firstname   = $request->firstname;
        $seller->lastname    = $request->lastname;
        $seller->mobile      = $request->mobile;
        $seller->email       = $request->email;

        $seller->address     = $request->address;
        $seller->province_id = $request->province_id;
        $seller->ward_id     = $request->ward_id;
        $seller->dial_code    = '84';

        $seller->status     = $request->status ? Status::USER_ACTIVE : Status::USER_BAN;
        $seller->ev         = $request->ev ? Status::VERIFIED : Status::UNVERIFIED;
        $seller->sv         = $request->sv ? Status::VERIFIED : Status::UNVERIFIED;
        $seller->ts         = $request->ts ? Status::ENABLE : Status::DISABLE;
        $seller->tv         = $request->tv ? Status::VERIFIED : Status::UNVERIFIED;
        $seller->kv         = $request->kv ? Status::VERIFIED : Status::UNVERIFIED;
        $seller->save();

        $notify[] = ['success', __('Seller detail has been updated')];
        return back()->withNotify($notify);
    }

    public function approve($id)
    {
        $seller = User::seller()->findOrFail($id);
        $seller->seller_active = 1;
        $seller->seller_activated_at = now();
        $seller->save();

        notify($seller, 'SELLER_APPROVE', [
            'seller_name' => $seller->fullname
        ]);

        $notify[] = ['success', __('Seller approved successfully')];
        return back()->withNotify($notify);
    }

    public function reject($id)
    {
        $seller = User::seller()->findOrFail($id);
        $seller->is_seller = 0; // Or keep it as seller but inactive?
        // Let's just ban them or keep them as normal user
        $seller->seller_active = 2; // Let's use 2 for Rejected
        $seller->save();

        notify($seller, 'SELLER_REJECT', [
            'seller_name' => $seller->fullname
        ]);

        $notify[] = ['success', __('Seller rejected successfully')];
        return back()->withNotify($notify);
    }

    public function login($id)
    {
        $seller = User::findOrFail($id);
        $seller->tv = $seller->ts == Status::ENABLE ? Status::UNVERIFIED : Status::VERIFIED;
        $seller->save();
        Auth::loginUsingId($id);
        return redirect()->route('seller.home');
    }

    public function shopDetails($sellerID)
    {
        $seller = User::findOrFail($sellerID);
        $pageTitle = __("Shop Details of") . " : $seller->username";
        if ($seller->shop) {
            $shop = $seller->shop;
        } else {
            $notify[] = ['error', __('Shop not found!')];
            return back()->withNotify($notify);
        }

        return view('admin.seller.shop_details', compact('shop', 'pageTitle', 'seller'));
    }

    public function shopUpdate(Request $request)
    {
        $seller         = User::findOrFail($request->seller_id);
        $shop           = Shop::where('seller_id', $seller->id)->first();
        $logoValidation = $coverValidation = 'required';

        if ($shop) {
            $logoValidation     = $shop->logo ? 'nullable' : 'required';
            $coverValidation    = $shop->cover ? 'nullable' : 'required';
        }

        $request->validate([
            'name'                  => 'required|string|max:40',
            'phone'                 => 'required|string|max:40',
            'address'               => 'required|string|max:600',
            'opening_time'          => 'nullable|date_format:H:i',
            'closing_time'          => 'nullable|date_format:H:i',
            'meta_title'            => 'nullable|string|max:191',
            'meta_description'      => 'nullable|string|max:191',
            'meta_keywords'         => 'nullable|array',
            'meta_keywords.array.*' => 'string',
            'social_links'          => 'nullable|array',
            'social_links.*.name'   => 'required_with:social_links|string',
            'social_links.*.icon'   => 'required_with:social_links|string',
            'social_links.*.link'   => 'required_with:social_links|string',

            'image'                 => [$logoValidation, 'max:2048', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'cover_image'           => [$coverValidation,  new FileTypeValidate(['jpg', 'jpeg', 'png'])]
        ], [
            'firstname.required'                  => __('First name field is required'),
            'lastname.required'                   => __('Last name field is required'),
            'social_links.*.name.required_with'   => __('All specification name is required'),
            'social_links.*.icon.required_with'   => __('All specification icon is required'),
            'social_links.*.link.required_with'   => __('All specification link is required'),
            'cover_image.required'                => __('Cover is required'),
        ], [
            'image' => 'logo'
        ]);

        if (!$shop) $shop = new Shop();


        if ($request->hasFile('image')) {
            $location       = getFilePath('sellerShopLogo');
            $shop->logo     = fileUploader($request->image, $location, null, @$shop->logo);
        }

        if ($request->hasFile('cover_image')) {
            $location       = getFilePath('sellerShopCover');
            $size           = getFileSize('sellerShopCover');
            $shop->cover    = fileUploader($request->cover_image, $location, $size, @$seller->cover_image);
        }

        $shop->name              = $request->name;
        $shop->seller_id         = $seller->id;
        $shop->phone             = $request->phone;
        $shop->address           = $request->address;
        $shop->opens_at          = $request->opening_time;
        $shop->closed_at         = $request->closing_time;
        $shop->meta_title        = $request->meta_title;
        $shop->meta_description  = $request->meta_description;
        $shop->meta_keywords     = $request->meta_keywords ?? null;
        $shop->social_links      = $request->social_links ?? null;
        $shop->save();

        $notify[] = ['success', __('Updated successfully')];
        return back()->withNotify($notify);
    }

    public function featureStatus($id)
    {
        $seller = User::find($id);
        if (!$seller) {
            return responseError('seller_not_found', [__('Seller not found')]);
        }

        $seller->featured = $seller->featured == Status::YES ? Status::NO : Status::YES;
        $message = $seller->featured == Status::YES ? __("Seller added to featured list") : __("Seller removed from featured list");
        $seller->save();

        $notify[] = $message;
        return responseSuccess('status_updated', $notify);
    }

    public function addSubBalance(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'act' => 'required|in:add,sub',
            'remark' => 'required|string|max:255',
        ]);

        $seller = User::findOrFail($id);
        $amount = $request->amount;
        $trx = getTrx();

        $transaction = new Transaction();

        if ($request->act == 'add') {
            $seller->balance += $amount;

            $transaction->trx_type = '+';
            $transaction->remark = 'balance_add';

            $notifyTemplate = 'BAL_ADD';

            $notify[] = ['success', __('Balance added successfully')];
        } else {
            if ($amount > $seller->balance) {
                $notify[] = ['error', $seller->username . ' ' . __("doesn't have sufficient balance.")];
                return back()->withNotify($notify);
            }

            $seller->balance -= $amount;

            $transaction->trx_type = '-';
            $transaction->remark = 'balance_subtract';

            $notifyTemplate = 'BAL_SUB';
            $notify[] = ['success', __('Balance subtracted successfully')];
        }

        $seller->save();

        $transaction->seller_id = $seller->id;
        $transaction->amount = $amount;
        $transaction->post_balance = $seller->balance;
        $transaction->charge = 0;
        $transaction->trx =  $trx;
        $transaction->details = $request->remark;
        $transaction->save();

        notify($seller, $notifyTemplate, [
            'trx' => $trx,
            'amount' => showAmount($amount, currencyFormat: false),
            'remark' => $request->remark,
            'post_balance' => showAmount($seller->balance, currencyFormat: false)
        ]);

        return back()->withNotify($notify);
    }


    public function showNotificationSingleForm($id)
    {
        $seller = User::findOrFail($id);
        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', __('Notification options are disabled currently')];
            return to_route('admin.sellers.detail', $seller->id)->withNotify($notify);
        }
        $pageTitle = __('Send Notification to') . ' ' . $seller->username;
        return view('admin.seller.notification_single', compact('pageTitle', 'seller'));
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

        $seller = User::findOrFail($id);
        notify($seller, 'DEFAULT', [
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

        $notifyToUser = User::notifyToSeller();
        $sellers        = User::active()->count();
        $pageTitle    = __('Notification to Verified Sellers');

        if (session()->has('SEND_NOTIFICATION') && !request()->email_sent) {
            session()->forget('SEND_NOTIFICATION');
        }

        return view('admin.seller.notification_all', compact('pageTitle', 'sellers', 'notifyToUser'));
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
            'number_of_top_deposited_user' => 'required_if:being_sent_to,topDepositedSellers|integer|gte:0',
            'number_of_days'               => 'required_if:being_sent_to,notLoginSellers|integer|gte:0',
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

        if ($request->being_sent_to == 'selectedSellers') {
            if (session()->has("SEND_NOTIFICATION")) {
                $request->merge(['seller' => session()->get('SEND_NOTIFICATION')['seller']]);
            } else {
                if (!$request->seller || !is_array($request->seller) || empty($request->seller)) {
                    $notify[] = ['error', __("Ensure that the seller field is populated when sending an email to the designated seller group")];
                    return back()->withNotify($notify);
                }
            }
        }

        $scope          = $request->being_sent_to;
        $sellerQuery      = User::oldest()->active()->$scope();

        if (session()->has("SEND_NOTIFICATION")) {
            $totalUserCount = session('SEND_NOTIFICATION')['total_user'];
        } else {
            $totalUserCount = (clone $sellerQuery)->count() - ($request->start - 1);
        }


        if ($totalUserCount <= 0) {
            $notify[] = ['error', __("Notification recipients were not found among the selected user base.")];
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

        $sellers = (clone $sellerQuery)->skip($request->start - 1)->limit($request->batch)->get();

        foreach ($sellers as $seller) {
            notify($seller, 'DEFAULT', [
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
            $url     = route("admin.sellers.notification.all");
        } else {
            session()->put('SEND_NOTIFICATION', $sessionData);
            $message = $sessionData['total_sent'] . " " . $sessionData['via'] . " " . __("notifications were sent successfully");
            $url     = route("admin.sellers.notification.all") . "?email_sent=yes";
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
        $sellers = $query->orderBy('id', 'desc')->paginate(getPaginate());
        return response()->json([
            'success' => true,
            'sellers'   => $sellers,
            'more'    => $sellers->hasMorePages()
        ]);
    }

    public function notificationLog($id)
    {
        $seller = User::findOrFail($id);
        $pageTitle = __('Notifications Sent to') . ' ' . $seller->username;
        $logs = NotificationLog::where('seller_id', $id)->with('seller')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.reports.notifications.index', compact('pageTitle', 'logs'));
    }


}
