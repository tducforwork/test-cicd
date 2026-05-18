<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductStock;
use App\Models\User;
use App\Models\SellLog;
use App\Models\StockLog;
use App\Models\SubOrder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Transaction;
use App\Models\AdminNotification;

class OrderController extends Controller
{
    public function allOrders($userId = null)
    {
        $pageTitle     = __("Quản lý đơn hàng");
        $orders        = $this->orderData(userId: $userId);
        $exportRoute   = 'admin.order.export';
        return view('admin.order.index', compact('pageTitle', 'orders', 'exportRoute'));
    }

    public function codOrders($userId = null)
    {
        $pageTitle     = __("Cash On Delivery Orders");
        $orders        = $this->orderData('cod', userId: $userId);
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function pending($userId = null)
    {
        $pageTitle     = __("Pending Orders");
        $orders        = $this->orderData('pending', userId: $userId);
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function processing($userId = null)
    {
        $pageTitle     = __("Orders on Processing");
        $orders        = $this->orderData('processing', userId: $userId);
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function readyToPickup()
    {
        $pageTitle = __("Ready to Pickup Sub Orders");

        $subOrders = SubOrder::ReadyToPickup()->searchable(['order_number', 'order:order_number', 'orderDetail'])->with('order.user')->orderBy('order_id', 'DESC')->paginate(getPaginate());
        return view('admin.order.suborder.ready_to_pickup', compact('pageTitle', 'subOrders'));
    }

    public function readyToDeliver($userId = null)
    {
        $pageTitle = __("Ready to Deliver Orders");
        $orders  = $this->orderData('readyToDeliver', userId: $userId);
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function dispatched($userId = null)
    {
        $pageTitle     = __("Orders Dispatched");
        $orders        = $this->orderData('dispatched', userId: $userId);
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function canceledOrders($userId = null)
    {
        $pageTitle     = __("Canceled Orders");
        $orders        = $this->orderData('canceled', userId: $userId);
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function deliveredOrders($userId = null)
    {
        $pageTitle     = __("Đơn đã hoàn thành (Hậu kiểm)");
        $orders        = $this->orderData('delivered', userId: $userId);
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function settledOrders($userId = null)
    {
        $pageTitle     = __("Đã quyết toán");
        $orders        = $this->orderData('completed', userId: $userId);
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function disputedOrders($userId = null)
    {
        $pageTitle     = __("Đang khiếu nại");
        $orders        = $this->orderData('disputed', userId: $userId);
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    private function orderData($scope = null, $userId = null, $isExport = false)
    {
        $query = SubOrder::valid()->orderNotCanceled();

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
            elseif ($status == 'canceled') $query->canceled();
        }

        if (request()->date) {
            $date = explode('-', request()->date);
            $start = \Illuminate\Support\Carbon::parse(trim($date[0]))->format('Y-m-d') . ' 00:00:00';
            $end = \Illuminate\Support\Carbon::parse(trim($date[1]))->format('Y-m-d') . ' 23:59:59';
            $query->whereBetween('created_at', [$start, $end]);
        }

        if ($userId) {
            $query->whereHas('order', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        }

        if (request()->search) {
            $query->searchable(['order_number', 'order:order_number']);
        }

        if (request()->self_order) {
            $query->where('seller_id', 0);
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
        $orders = $this->orderData(isExport: true);
        $filename = "orders_" . now()->format('YmdHis') . ".csv";
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
            'Người bán', 
            'Số lượng SP', 
            'Tiền hàng', 
            'Phí vận chuyển', 
            'Tổng cộng', 
            'Thanh toán', 
            'Trạng thái'
        ];

        $callback = function () use ($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // Add BOM for UTF-8 support in Excel
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                $status = '';
                if ($order->status == Status::SUBORDER_PENDING) $status = 'Chờ xác nhận';
                elseif ($order->status == Status::SUBORDER_PROCESSING) $status = 'Đang xử lý';
                elseif ($order->status == Status::SUBORDER_READY_TO_PICKUP) $status = 'Đóng gói xong';
                elseif ($order->status == Status::SUBORDER_DISPATCHED) $status = 'Đang vận chuyển';
                elseif ($order->status == Status::SUBORDER_DELIVERED) $status = 'Đã giao';
                elseif ($order->status == Status::SUBORDER_COMPLETED) $status = 'Hoàn thành';
                elseif ($order->status == Status::SUBORDER_DISPUTED) $status = 'Khiếu nại';
                elseif ($order->status == Status::SUBORDER_REJECTED) $status = 'Đã hủy';

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
                    ' ' . $order->order->user->mobile, // Adding a space to force string in Excel
                    $order->seller_id == 0 ? 'Admin' : (@$order->seller->shop->name ?? 'Shop'),
                    $order->total_products,
                    showAmount($order->total_amount, currencyFormat: false),
                    showAmount($order->order->shipping_charge, currencyFormat: false),
                    showAmount($order->total_amount + $order->order->shipping_charge, currencyFormat: false),
                    $payStatus,
                    $status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function changeStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:sub_orders,id',
            'action' => ['required', Rule::in([Status::SUBORDER_PENDING, Status::SUBORDER_DELIVERED, Status::SUBORDER_PROCESSING, Status::SUBORDER_DISPATCHED, Status::SUBORDER_READY_TO_PICKUP, Status::SUBORDER_REJECTED, Status::SUBORDER_COMPLETED, Status::SUBORDER_DISPUTED])],
        ]);

        $suborder = SubOrder::with('order.user', 'orderDetail.product')->findOrFail($request->id);
        $order = $suborder->order;

        if ($suborder->status == Status::SUBORDER_COMPLETED && $request->action != Status::SUBORDER_COMPLETED) {
            $notify[] = ['error', __('This sub-order has already been settled')];
            return back()->withNotify($notify);
        }

        $suborder->status = $request->action;
        $suborder->save();

        $actionName = 'Updated';
        if ($request->action == Status::SUBORDER_PROCESSING) {
            $actionName = 'Processing';
        } elseif ($request->action == Status::SUBORDER_DISPATCHED) {
            $actionName = 'Dispatched';
        } elseif ($request->action == Status::SUBORDER_DELIVERED) {
            $actionName = 'Delivered';

            if ($order->payment_status != Status::PAYMENT_SUCCESS) {
                // Payment success can be marked here or at settlement. 
                // For COD, usually it's success once delivered.
            }
        } elseif ($request->action == Status::SUBORDER_COMPLETED) {
            $actionName = 'Settled';

            foreach ($suborder->orderDetail as $detail) {
                $finalAmount = $detail->total_price;
                $detail->product->sold += $detail->quantity;
                $detail->product->save();

                $sellLog = new SellLog();
                $sellLog->seller_id       = $suborder->seller_id;
                $sellLog->product_id      = $detail->product_id;
                $sellLog->order_id        = $order->order_number;
                $sellLog->qty             = $detail->quantity;
                $sellLog->product_price   = $detail->total_price;
                $sellLog->product_commission = 0;
                $sellLog->after_commission = $suborder->seller_id == 0 ? 0 : $finalAmount;
                $sellLog->save();
            }
        } elseif ($request->action == Status::SUBORDER_DISPUTED) {
            $actionName = 'Disputed';
        } elseif ($request->action == Status::SUBORDER_REJECTED) {
            $actionName = 'Rejected';
            StockLog::restoreStock($suborder->id, true);

            $order->total_amount -= ($suborder->total_amount + $suborder->shipping_charge);
            $order->shipping_charge -= $suborder->shipping_charge;
            $order->save();

            // Auto-refund online payment successful orders
            if ($order->payment_status == Status::PAYMENT_SUCCESS && @$order->user) {
                $user = $order->user;
                $refundAmount = $suborder->total_amount + $suborder->shipping_charge;
                $user->balance += $refundAmount;
                $user->save();

                $transaction = new \App\Models\Transaction();
                $transaction->user_id = $user->id;
                $transaction->amount = $refundAmount;
                $transaction->post_balance = $user->balance;
                $transaction->charge = 0;
                $transaction->trx_type = '+';
                $transaction->details = 'Refunded ' . showAmount($refundAmount) . ' due to admin rejecting suborder #' . $suborder->order_number;
                $transaction->trx = getTrx();
                $transaction->remark = 'suborder_refund';
                $transaction->save();
            }
        }

        $order->syncStatus();

        $notify[] = ['success', __('Sub-order status changed to') . ' ' . $actionName];
        
        $shortCodes = [
            'site_name' => gs('sitename'),
            'order_id'  => $order->order_number,
            'shop_name' => $suborder->seller_id == 0 ? 'Quản trị viên' : (@$suborder->seller->shop->name ?? 'Shop'),
            'status'    => strtolower($actionName)
        ];
        
        return back()->withNotify($notify);
    }

    public function orderDetails($id)
    {
        $order = Order::with('user', 'subOrders.seller', 'subOrders.orderDetail.product')->findOrFail($id);
        $pageTitle = __('Order Details');
        return view('admin.order.order_details', compact('order', 'pageTitle'));
    }

    public function invoice($id)
    {
        $order = Order::with('user', 'deposit', 'deposit.gateway', 'orderDetail', 'appliedCoupon')->findOrFail($id);
        $pageTitle = __('Invoice of') . ' #' . $order->order_number;
        return view('admin.order.invoice', compact('order', 'pageTitle'));
    }

    public function printInvoice($id)
    {
        $order = Order::with('user', 'deposit', 'deposit.gateway', 'orderDetail', 'appliedCoupon')->findOrFail($id);
        $pageTitle = __('Print Invoice');
        return view('admin.order.print_invoice', compact('pageTitle', 'order'));
    }

    public function adminSellsLog()
    {
        $pageTitle     = __("My Sales");
        $logs          = SellLog::searchable(['order_id'])->where('seller_id', 0)->orderBy('id', 'DESC')->paginate(getPaginate());
        return view('admin.order.sell_log', compact('pageTitle', 'logs'));
    }

    public function sellerSellsLog()
    {
        $pageTitle     = __("Seller Sales Log");
        $logs          = SellLog::searchable(['order_id'])->where('seller_id', '!=', 0)->orderBy('id', 'DESC')->paginate(getPaginate());

        return view('admin.order.sell_log', compact('pageTitle', 'logs'));
    }

    public function refundOrder(Request $request, $id)
    {
        $request->validate([
            'is_refunded' => 'required|in:1'
        ]);

        $order = Order::where('payment_status', Status::PAYMENT_SUCCESS)->where('status', Status::ORDER_CANCELED)->findOrFail($id);
        $order->is_refunded = Status::YES;
        $order->save();

        $notify[] = ['success', __('Refund status updated successfully')];
        return back()->withNotify($notify);
    }

    public function refundSubOrder(Request $request, $id)
    {
        $request->validate([
            'is_refunded' => 'required|in:1'
        ]);

        $suborder = SubOrder::orderNotCanceled()->with('order')->findOrfail($id);
        if ($suborder->order->payment_status != Status::PAYMENT_SUCCESS) {
            $notify[] = ['error', __('Invalid Order')];
            return back()->withNotify($notify);
        }

        $suborder->is_refunded = Status::YES;
        $suborder->save();

        $notify[] = ['success', __('Refund status updated successfully')];
        return back()->withNotify($notify);
    }

    public function payoutSubOrder(Request $request, $id)
    {
        $suborder = SubOrder::where('status', Status::SUBORDER_COMPLETED)->where('is_payout', Status::NO)->findOrFail($id);
        $seller   = User::findOrFail($suborder->seller_id);

        $amount = $suborder->total_amount;

        $seller->balance += $amount;
        $seller->save();

        $transaction               = new Transaction();
        $transaction->seller_id    = $seller->id;
        $transaction->amount       = $amount;
        $transaction->post_balance = $seller->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '+';
        $transaction->details      = 'Payout for suborder: #' . $suborder->order_number;
        $transaction->trx          = getTrx();
        $transaction->remark       = 'suborder_payout';
        $transaction->save();

        $suborder->is_payout = Status::YES;
        $suborder->payout_at = now();
        $suborder->save();

        notify($seller, 'BAL_ADD', [
            'trx'          => $transaction->trx,
            'amount'       => showAmount($amount),
            'remark'       => 'Thanh toán cho đơn hàng con: #' . $suborder->order_number,
            'post_balance' => showAmount($seller->balance)
        ]);

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $seller->id;
        $adminNotification->title     = 'Đã thanh toán cho người bán: ' . $seller->username . ' đơn hàng #' . $suborder->order_number;
        $adminNotification->click_url = urlPath('admin.order.details', $suborder->order_id);
        $adminNotification->save();

        $notify[] = ['success', __('Số dư đã được cộng cho người bán thành công')];
        return back()->withNotify($notify);
    }
}
