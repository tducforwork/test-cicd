<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\CurlRequest;
use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use App\Models\SellLog;
use App\Models\Transaction;
use App\Models\UserLogin;
use App\Models\Withdrawal;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function dashboard()
    {
        $pageTitle = __("All Shop's Analytics");

        $widget['total_users'] = User::count();
        $widget['verified_users'] = User::where('status', Status::USER_ACTIVE)->count();
        $widget['all_orders']               = Order::valid()->count();
        $widget['delivered_orders']         = Order::valid()->where('status', Status::ORDER_DELIVERED)->count();

        $widget['total_product']            = Product::whereHas('brand')->whereHas('categories')->count();

        $widget['total_sell_amount']        = Deposit::where('status', Status::PAYMENT_SUCCESS)->sum('amount');
        $widget['total_seller']             = User::seller()->count();
        $widget['total_active_seller']      = User::seller()->active()->count();

        // user Browsing, Country, Operating Log
        $userLoginData = UserLogin::where('created_at', '>=', Carbon::now()->subDays(30))->get(['browser', 'os', 'country']);

        $chart['user_browser_counter'] = $userLoginData->groupBy('browser')->map(function ($item, $key) {
            return collect($item)->count();
        });

        $chart['user_os_counter'] = $userLoginData->groupBy('os')->map(function ($item, $key) {
            return collect($item)->count();
        });

        $chart['user_country_counter'] = $userLoginData->groupBy('country')->map(function ($item, $key) {
            return collect($item)->count();
        })->sort()->reverse()->take(5);

        $deposit['total_deposit_amount']        = Deposit::successful()->sum('amount');
        $deposit['total_deposit_pending']       = Deposit::pending()->count();
        $deposit['total_deposit_rejected']      = Deposit::rejected()->count();
        $deposit['total_deposit_charge']        = Deposit::successful()->sum('charge');

        $withdrawals['total_withdraw_amount']   = Withdrawal::approved()->sum('amount');
        $withdrawals['total_withdraw_pending']  = Withdrawal::pending()->count();
        $withdrawals['total_withdraw_rejected'] = Withdrawal::rejected()->count();
        $withdrawals['total_withdraw_charge']   = Withdrawal::approved()->sum('charge');

        return view('admin.dashboard', compact('pageTitle', 'widget', 'chart', 'deposit', 'withdrawals'));
    }

    public function myDashboard()
    {
        $pageTitle = __("My Shop's Analytics");

        $order['all']        = $this->getAdminOrders()->count();
        $order['pending']    = $this->getAdminOrders('pending')->count();
        $order['processing'] = $this->getAdminOrders('processing')->count();
        $order['dispatched'] = $this->getAdminOrders('dispatched')->count();
        $order['delivered']  = $this->getAdminOrders('delivered')->count();
        $order['canceled']   = $this->getAdminOrders('canceled')->count();
        $order['cod']        = $this->getAdminOrders('cod')->count();

        $product['total']     = Product::where('seller_id', 0)->count();
        $product['total_sold'] = Product::where('seller_id', 0)->sum('sold');
        $product['top_selling_products']     = Product::topSales(3);

        $sale['last_seven_days']          = SellLog::where('seller_id', 0)->where('created_at', '>=', Carbon::today()->subDays(7))->sum('product_price');
        $sale['last_fifteen_days']        = SellLog::where('seller_id', 0)->where('created_at', '>=', Carbon::today()->subDays(15))->sum('product_price');
        $sale['last_thirty_days']         = SellLog::where('seller_id', 0)->where('created_at', '>=', Carbon::today()->subDays(30))->sum('product_price');

        return view('admin.my_dashboard', compact('pageTitle', 'order', 'product', 'sale'));
    }

    private function getAdminOrders($scope = null)
    {
        if ($scope) {
            $query = Order::valid()->$scope();
        } else {
            $query = Order::valid();
        }

        $orders = $query->whereHas('subOrders', function ($query) {
            $query->where('seller_id', 0);
        });

        return $orders;
    }

    public function salesAndWithdrawReport(Request $request)
    {

        $diffInDays = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date));

        $groupBy = $diffInDays > 30 ? 'months' : 'days';
        $format = $diffInDays > 30 ? '%M-%Y'  : '%d-%M-%Y';

        if ($groupBy == 'days') {
            $dates = $this->getAllDates($request->start_date, $request->end_date);
        } else {
            $dates = $this->getAllMonths($request->start_date, $request->end_date);
        }
        $deposits = Deposit::successful()
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->latest()
            ->groupBy('created_on')
            ->get();


        $withdrawals = Withdrawal::approved()
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->latest()
            ->groupBy('created_on')
            ->get();

        $data = [];

        foreach ($dates as $date) {
            $data[] = [
                'created_on' => $date,
                'deposits' => getAmount($deposits->where('created_on', $date)->first()?->amount ?? 0),
                'withdrawals' => getAmount($withdrawals->where('created_on', $date)->first()?->amount ?? 0)
            ];
        }

        $data = collect($data);

        // Monthly Deposit & Withdraw Report Graph
        $report['created_on']   = $data->pluck('created_on');
        $report['data']     = [
            [
                'name' => 'Sales',
                'data' => $data->pluck('deposits')
            ],
            [
                'name' => __('Withdrawn'),
                'data' => $data->pluck('withdrawals')
            ]
        ];

        return response()->json($report);
    }

    public function transactionReport(Request $request)
    {

        $diffInDays = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date));

        $groupBy = $diffInDays > 30 ? 'months' : 'days';
        $format = $diffInDays > 30 ? '%M-%Y'  : '%d-%M-%Y';

        if ($groupBy == 'days') {
            $dates = $this->getAllDates($request->start_date, $request->end_date);
        } else {
            $dates = $this->getAllMonths($request->start_date, $request->end_date);
        }

        $plusTransactions   = Transaction::where('trx_type', '+')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->latest()
            ->groupBy('created_on')
            ->get();

        $minusTransactions  = Transaction::where('trx_type', '-')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->latest()
            ->groupBy('created_on')
            ->get();


        $data = [];

        foreach ($dates as $date) {
            $data[] = [
                'created_on' => $date,
                'credits' => getAmount($plusTransactions->where('created_on', $date)->first()?->amount ?? 0),
                'debits' => getAmount($minusTransactions->where('created_on', $date)->first()?->amount ?? 0)
            ];
        }

        $data = collect($data);

        // Monthly Deposit & Withdraw Report Graph
        $report['created_on']   = $data->pluck('created_on');
        $report['data']     = [
            [
                'name' => __('Plus Transactions'),
                'data' => $data->pluck('credits')
            ],
            [
                'name' => __('Minus Transactions'),
                'data' => $data->pluck('debits')
            ]
        ];

        return response()->json($report);
    }

    public function mySalesReport(Request $request)
    {
        $diffInDays = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date));

        $groupBy = $diffInDays > 30 ? 'months' : 'days';
        $format = $diffInDays > 30 ? '%M-%Y'  : '%d-%M-%Y';

        if ($groupBy == 'days') {
            $dates = $this->getAllDates($request->start_date, $request->end_date);
        } else {
            $dates = $this->getAllMonths($request->start_date, $request->end_date);
        }

        $sales = SellLog::where('seller_id', 0)
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->selectRaw("SUM(product_price) as totalAmount")
            ->selectRaw("DATE_FORMAT(created_at,'{$format}') as created_on")
            ->orderBy('created_at')
            ->groupBy('created_on')->get();

        $data = [];

        foreach ($dates as $date) {
            $data[] = [
                'created_on' => $date,
                'amount'     => getAmount($sales->where('created_on', $date)->first()?->totalAmount ?? 0)
            ];
        }

        $data = collect($data);

        $report['created_on']   = $data->pluck('created_on');
        $report['data'] = [
            [
                'name' => 'Sales',
                'data' => $data->pluck('amount')
            ]
        ];

        return response()->json($report);
    }

    private function getAllDates($startDate, $endDate)
    {
        $dates = [];
        $currentDate = new \DateTime($startDate);
        $endDate = new \DateTime($endDate);

        while ($currentDate <= $endDate) {
            $dates[] = $currentDate->format('d-F-Y');
            $currentDate->modify('+1 day');
        }

        return $dates;
    }

    private function  getAllMonths($startDate, $endDate)
    {
        if ($endDate > now()) {
            $endDate = now()->format('Y-m-d');
        }

        $startDate = new \DateTime($startDate);
        $endDate = new \DateTime($endDate);

        $months = [];

        while ($startDate <= $endDate) {
            $months[] = $startDate->format('F-Y');
            $startDate->modify('+1 month');
        }

        return $months;
    }


    public function profile()
    {
        $pageTitle = __('Profile');
        $admin = auth('admin')->user();
        return view('admin.profile', compact('pageTitle', 'admin'));
    }

    public function profileUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])]
        ]);
        $user = auth('admin')->user();

        if ($request->hasFile('image')) {
            try {
                $old = $user->image;
                $user->image = fileUploader($request->image, getFilePath('adminProfile'), getFileSize('adminProfile'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', __('Couldn\'t upload your image')];
                return back()->withNotify($notify);
            }
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        $notify[] = ['success', __('Profile updated successfully')];
        return to_route('admin.profile')->withNotify($notify);
    }

    public function password()
    {
        $pageTitle = __('Password Setting');
        $admin = auth('admin')->user();
        return view('admin.password', compact('pageTitle', 'admin'));
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:5|confirmed',
        ]);

        $user = auth('admin')->user();
        if (!Hash::check($request->old_password, $user->password)) {
            $notify[] = ['error', __('Password doesn\'t match!!')];
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => __('The current password doesn\'t match!')], 400);
            }
            return back()->withNotify($notify);
        }
        $user->password = Hash::make($request->password);
        $user->save();
            return response()->json(['status' => 'success', 'message' => __('Password changed successfully.')]);
        return to_route('admin.password')->withNotify($notify);
    }

    public function notifications()
    {
        $notifications = AdminNotification::orderBy('id', 'desc')->with('user')->paginate(getPaginate());
        $hasUnread = AdminNotification::where('is_read', Status::NO)->exists();
        $hasNotification = AdminNotification::exists();
        $pageTitle = __('Notifications');
        return view('admin.notifications', compact('pageTitle', 'notifications', 'hasUnread', 'hasNotification'));
    }


    public function notificationRead($id)
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->is_read = Status::YES;
        $notification->save();
        $url = $notification->click_url;
        if ($url == '#') {
            $url = url()->previous();
        }
        return redirect($url);
    }

    public function readAllNotification()
    {
        AdminNotification::where('is_read', Status::NO)->update([
            'is_read' => Status::YES
        ]);
        $notify[] = ['success', __('Notifications read successfully')];
        return back()->withNotify($notify);
    }

    public function deleteAllNotification()
    {
        AdminNotification::truncate();
        $notify[] = ['success', __('Notifications deleted successfully')];
        return back()->withNotify($notify);
    }

    public function deleteSingleNotification($id)
    {
        AdminNotification::where('id', $id)->delete();
        $notify[] = ['success', __('Notification deleted successfully')];
        return back()->withNotify($notify);
    }

    public function downloadAttachment($fileHash)
    {
        $filePath = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $title = slug(gs('site_name')) . '- attachments.' . $extension;
        try {
            $mimetype = mime_content_type($filePath);
        } catch (\Exception $e) {
            $notify[] = ['error', __('File does not exists')];
            return back()->withNotify($notify);
        }
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function editorUpload(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])]
        ]);

        try {
            $path = getFilePath('editor');
            $image = fileUploader($request->image, $path);
            return response()->json([
                'data' => [
                    'link' => getImage($path . '/' . $image)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'data' => [
                    'error' => __('Could not upload your image')
                ]
            ]);
        }
    }
}
