<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StockLog;
use App\Models\SubOrder;
use App\Models\AdminNotification;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function all()
    {
        $pageTitle      = __("Quản lý đơn hàng của tôi");
        $orders         = $this->getOrders();
        $exportRoute    = 'admin.suborder.export';
        return view('admin.order.index', compact('pageTitle', 'orders', 'exportRoute'));
    }

    public function pending()
    {
        $pageTitle      = __("Đơn chờ xác nhận");
        $orders         = $this->getOrders('pending');
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function processing()
    {
        $pageTitle      = __('Đơn đang đóng gói');
        $orders         = $this->getOrders('processing');
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function readyToPickup()
    {
        $pageTitle      = __('Đơn chờ gửi đi');
        $orders         = $this->getOrders('readyToPickup');
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function dispatched()
    {
        $pageTitle      = __('Đơn đã gửi đi');
        $orders         = $this->getOrders('dispatched');
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function delivered()
    {
        $pageTitle      = __('Đơn đã hoàn thành (Chờ gọi xác nhận)');
        $orders         = $this->getOrders('delivered');
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function settled()
    {
        $pageTitle      = __('Đơn đã quyết toán');
        $orders         = $this->getOrders('completed');
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function disputed()
    {
        $pageTitle      = __('Đơn đang khiếu nại');
        $orders         = $this->getOrders('disputed');
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function detail($id)
    {
        $suborder  = SubOrder::valid()->with('order.user', 'order.shippingMethod', 'order.appliedCoupon', 'orderDetail.product')->findOrFail($id);
        $pageTitle = __('Chi tiết đơn hàng');

        return view('admin.order.suborder.details', compact('pageTitle', 'suborder'));
    }

    public function rejected()
    {
        $pageTitle     = __("Đơn bị từ chối");
        $orders        = $this->getOrders('rejected');
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function markAsProcessing($id)
    {
        $suborder = SubOrder::valid()->orderNotCanceled()->pending()->with('order.user')->findOrFail($id);
        $suborder->status = Status::SUBORDER_PROCESSING;
        $suborder->save();

        $order = $suborder->order;
        if ($order->status == Status::ORDER_PENDING) {
            $order->status = Status::ORDER_PROCESSING;
            $order->save();

            if ($order->user) {
                notify($order->user, 'ORDER_ON_PROCESSING_CONFIRMATION', [
                    'site_name' => gs('sitename'),
                    'order_id'  => $order->order_number
                ]);
            }
        }

        $notify[] = ['success', __('Order marked as processing successfully')];
        return back()->withNotify($notify);
    }

    public function markAsReadyToPickUp($id)
    {
        $suborder = SubOrder::valid()->orderNotCanceled()->processing()->with('order.user')->findOrFail($id);
        $suborder->status = Status::SUBORDER_READY_TO_PICKUP;
        $suborder->save();

        $notify[] = ['success', __('Order marked as ready for pickup successfully')];
        return back()->withNotify($notify);
    }

    public function reject($id)
    {
        $suborder = SubOrder::valid()->orderNotCanceled()->pending()->with('orderDetail.product')->findOrFail($id);
        $suborder->status = Status::SUBORDER_REJECTED;
        $suborder->save();

        $order = Order::with('subOrders', 'user')->find($suborder->order_id);

        // update order amount
        $order->total_amount -= $suborder->total_amount;
        $order->save();

        // update product stock
        StockLog::restoreStock($suborder->id, true);

        // notify user
        if (@$order->user) {
            $products = $suborder->orderDetail->map(function ($item, $key) {
                return $item->product->name . ' (' . $item->quantity . ')';
            })->join(', ');

            notify($order->user, 'ORDER_ITEM_CANCELED', [
                'order_number' => $suborder->order_number,
                'products' => $products
            ]);
        }

        // check all suborders, if all suborders had been rejected then the order should be canceled automatically
        if ($order->subOrders->where('status', Status::SUBORDER_REJECTED)->count() == $order->subOrders->count()) {
            $order->autoCancel();
        }

        // admin notification
        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->guard('admin')->user()->id;
        $adminNotification->title = 'Admin rejected the order #' . $suborder->order_number;
        $adminNotification->click_url = urlPath('admin.order.details', $suborder->order_id);
        $adminNotification->save();

        $notify[] = ['success', __('Order has been rejected successfully')];
        return back()->withNotify($notify);
    }

    private function getOrders($scope = null, $isExport = false)
    {
        $query = SubOrder::admin()->valid()->orderNotCanceled();

        if ($scope) {
            $query->$scope();
        }

        if (request()->status) {
            $status = request()->status;
            if ($status == 'pending') $query->pending();
            elseif ($status == 'processing') $query->processing();
            elseif ($status == 'ready_to_pickup') $query->readyToPickup();
            elseif ($status == 'dispatched') $query->dispatched();
            elseif ($status == 'delivered') $query->delivered();
            elseif ($status == 'completed') $query->completed();
            elseif ($status == 'disputed') $query->disputed();
            elseif ($status == 'rejected') $query->rejected();
        }

        if (request()->date) {
            $date = explode('-', request()->date);
            $start = \Illuminate\Support\Carbon::parse(trim($date[0]))->format('Y-m-d') . ' 00:00:00';
            $end = \Illuminate\Support\Carbon::parse(trim($date[1]))->format('Y-m-d') . ' 23:59:59';
            $query->whereBetween('created_at', [$start, $end]);
        }

        if (request()->search) {
            $query->searchable(['order_number', 'order:order_number']);
        }

        $query = $query->with(['order.user', 'order.deposit.gateway', 'seller', 'orderDetail.product'])
            ->withSum('orderDetail as total_products', 'quantity')
            ->orderBy('id', 'DESC');

        if ($isExport) {
            return $query->get();
        }

        return $query->paginate(getPaginate());
    }

    public function export()
    {
        $orders = $this->getOrders(); // This will get the filtered results but we need ALL for export
        // Actually, we should call getOrders with a flag like OrderController does, 
        // but for now let's just use the current logic which might be paginated.
        // Wait, I should fix getOrders to support isExport.
        
        $orders = $this->getOrders(isExport: true);

        $filename = "my_orders_" . now()->format('YmdHis') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'Mã đơn hàng (con)', 
            'Mã đơn hàng (tổng)', 
            'Ngày đặt', 
            'Khách hàng', 
            'Email', 
            'Số điện thoại', 
            'Số lượng SP', 
            'Tiền hàng', 
            'Phí vận chuyển', 
            'Tổng cộng', 
            'Thanh toán', 
            'Trạng thái'
        ];

        $callback = function () use ($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                $payStatus = '';
                if ($order->order->payment_status == Status::PAYMENT_SUCCESS) $payStatus = 'Đã thanh toán';
                elseif ($order->order->payment_status == Status::COD) $payStatus = 'Thanh toán khi nhận hàng (COD)';
                else $payStatus = 'Chưa thanh toán';

                fputcsv($file, [
                    $order->order_number,
                    $order->order->order_number,
                    showDateTime($order->created_at),
                    $order->order->user->fullname,
                    $order->order->user->email,
                    ' ' . $order->order->user->mobile,
                    $order->total_products,
                    showAmount($order->total_amount, currencyFormat: false),
                    showAmount($order->order->shipping_charge, currencyFormat: false),
                    showAmount($order->total_amount + $order->order->shipping_charge, currencyFormat: false),
                    $payStatus,
                    $order->status_name
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
