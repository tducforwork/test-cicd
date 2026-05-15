<?php

namespace App\Http\Controllers\Seller;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Order;
use App\Models\StockLog;
use App\Models\SubOrder;

class OrderController extends Controller
{
    public function all()
    {
        $status = request()->status;
        $pageTitle = "Order Management";

        if ($status == 'pending') {
            $pageTitle = "Pending Orders";
        } elseif ($status == 'processing') {
            $pageTitle = 'Processing Orders';
        } elseif ($status == 'readyToPickup') {
            $pageTitle = 'Ready To Pickup Orders';
        } elseif ($status == 'delivered') {
            $pageTitle = 'Delivered Orders';
        } elseif ($status == 'rejected') {
            $pageTitle = "Rejected Orders";
        }

        $orders = $this->getOrders($status);

        // Calculate stats for the current seller
        $sellerSubOrders = SubOrder::valid()->belongsToSeller();
        $stats = [
            'pending' => (clone $sellerSubOrders)->where('status', Status::SUBORDER_PENDING)->count(),
            'processing' => (clone $sellerSubOrders)->where('status', Status::SUBORDER_PROCESSING)->count(),
            'readyToPickup' => (clone $sellerSubOrders)->where('status', Status::SUBORDER_READY_TO_PICKUP)->count(),
            'delivered' => (clone $sellerSubOrders)->where('status', Status::SUBORDER_DELIVERED)->count(),
            'rejected' => (clone $sellerSubOrders)->where('status', Status::SUBORDER_REJECTED)->count(),
        ];

        return view('seller.order.index', compact('pageTitle', 'orders', 'stats'));
    }

    public function orderDetails($id)
    {
        $suborder  = SubOrder::valid()->belongsToSeller()->with('order.user', 'order.shippingMethod', 'orderDetail.product')->findOrFail($id);
        $pageTitle = 'Order Details';
        return view('seller.order.details', compact('pageTitle', 'suborder'));
    }

    public function markAsProcessing($id)
    {
        $suborder = SubOrder::valid()->orderNotCanceled()->pending()->belongsToSeller()->with('order.user')->findOrFail($id);
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

        $notify[] = ['success', 'Order marked as processing successfully'];
        return back()->withNotify($notify);
    }

    public function markAsReadyToPickUp($id)
    {
        $suborder = SubOrder::valid()->orderNotCanceled()->processing()->belongsToSeller()->with('order.user')->findOrFail($id);
        $suborder->status = Status::SUBORDER_READY_TO_PICKUP;
        $suborder->save();

        $notify[] = ['success', 'Order marked as ready for pickup successfully'];
        return back()->withNotify($notify);
    }

    public function reject($id)
    {
        $suborder = SubOrder::valid()->orderNotCanceled()->pending()->belongsToSeller()->with('orderDetail.product')->findOrFail($id);
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
        $adminNotification->user_id = seller()->id;
        $adminNotification->title = 'Seller rejected the order #' . $suborder->order_number;
        $adminNotification->click_url = urlPath('admin.order.details', $suborder->order_id);
        $adminNotification->save();

        $notify[] = ['success', 'Order has been rejected successfully'];
        return back()->withNotify($notify);
    }

    private function getOrders($scope = null)
    {
        $query = SubOrder::valid()->orderNotCanceled()->belongsToSeller();

        if ($scope && $scope != 'all') {
            if (method_exists(SubOrder::class, 'scope' . ucfirst($scope))) {
                $query->$scope();
            }
        }

        return $query->searchable(['order_number'])->orderBy('id', 'DESC')->with('order', 'orderDetail.product')->withSum('orderDetail as total_products', 'quantity')->paginate(getPaginate());
    }

    public function markAsShipped($id)
    {
        $suborder = SubOrder::valid()->belongsToSeller()->with('order.user')->findOrFail($id);

        // Allowed transitions: from Pending(0), Processing(2), ReadyToPickUp(3) to Delivered(1)
        $suborder->status = Status::SUBORDER_DELIVERED;
        $suborder->save();

        $order = $suborder->order;
        // Check if all suborders are delivered, then mark parent order as delivered
        if ($order->subOrders->where('status', Status::SUBORDER_DELIVERED)->count() == $order->subOrders->count()) {
            $order->status = Status::ORDER_DELIVERED;
            $order->save();
        }

        $notify[] = ['success', 'Order marked as shipped/delivered successfully'];
        return back()->withNotify($notify);
    }

    public function changeStatus()
    {
        $request = request();
        $request->validate([
            'suborder_id' => 'required|integer',
            'status' => 'required|integer',
        ]);

        $suborder = SubOrder::valid()->belongsToSeller()->findOrFail($request->suborder_id);
        $newStatus = (int) $request->status;

        $allowed = [
            Status::SUBORDER_PENDING => [Status::SUBORDER_PROCESSING, Status::SUBORDER_REJECTED],
            Status::SUBORDER_PROCESSING => [Status::SUBORDER_DELIVERED, Status::SUBORDER_READY_TO_PICKUP],
            Status::SUBORDER_READY_TO_PICKUP => [Status::SUBORDER_DISPATCHED, Status::SUBORDER_DELIVERED],
            Status::SUBORDER_DISPATCHED => [Status::SUBORDER_DELIVERED],
        ];

        $allowedTransitions = $allowed[$suborder->status] ?? [];

        if (!in_array($newStatus, $allowedTransitions)) {
            $notify[] = ['error', 'Trạng thái không hợp lệ'];
            return back()->withNotify($notify);
        }

        $suborder->status = $newStatus;
        $suborder->save();

        $order = $suborder->order;

        if ($newStatus == Status::SUBORDER_PROCESSING) {
            if ($order->status == Status::ORDER_PENDING) {
                $order->status = Status::ORDER_PROCESSING;
                $order->save();
            }
            if ($order->user) {
                notify($order->user, 'ORDER_ON_PROCESSING_CONFIRMATION', [
                    'site_name' => gs('sitename'),
                    'order_id'  => $order->order_number
                ]);
            }
        }

        if ($newStatus == Status::SUBORDER_REJECTED) {
            $order->total_amount -= $suborder->total_amount;
            $order->save();
            StockLog::restoreStock($suborder->id, true);

            if (@$order->user) {
                $products = $suborder->orderDetail->map(fn($item) => $item->product->name . ' (' . $item->quantity . ')')->join(', ');
                notify($order->user, 'ORDER_ITEM_CANCELED', [
                    'order_number' => $suborder->order_number,
                    'products' => $products
                ]);
            }

            if ($order->subOrders->where('status', Status::SUBORDER_REJECTED)->count() == $order->subOrders->count()) {
                $order->autoCancel();
            }

            $adminNotification = new AdminNotification();
            $adminNotification->user_id = seller()->id;
            $adminNotification->title = 'Seller rejected the order #' . $suborder->order_number;
            $adminNotification->click_url = urlPath('admin.order.details', $suborder->order_id);
            $adminNotification->save();
        }

        if ($newStatus == Status::SUBORDER_DELIVERED) {
            if ($order->subOrders->where('status', Status::SUBORDER_DELIVERED)->count() == $order->subOrders->count()) {
                $order->status = Status::ORDER_DELIVERED;
                $order->save();
            }
        }

        if ($newStatus == Status::SUBORDER_DISPATCHED) {
            if ($order->subOrders->where('status', Status::SUBORDER_DISPATCHED)->count() == $order->subOrders->count()) {
                $order->status = Status::ORDER_DISPATCHED;
                $order->save();
            }
        }

        $notify[] = ['success', 'Cập nhật trạng thái thành công'];
        return back()->withNotify($notify);
    }
}
