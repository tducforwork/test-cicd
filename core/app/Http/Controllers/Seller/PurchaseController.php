<?php

namespace App\Http\Controllers\Seller;

use App\Constants\Status;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PurchaseController extends Controller
{
    public function orders($type = 'all')
    {
        $pageTitle = ($type == 'all' ? 'All' : ucfirst($type)) . ' Purchases';
        $emptyMessage = 'Không có đơn hàng nào';
        $query = Order::valid()->where('user_id', auth()->id())->whereNull('remark');

        if ($type == 'pending') {
            $query = $query->where('status', Status::ORDER_PENDING);
        } elseif ($type == 'processing') {
            $query = $query->where('status', Status::ORDER_PROCESSING);
        } elseif ($type == 'dispatched') {
            $query = $query->where('status', Status::ORDER_DISPATCHED);
        } elseif ($type == 'completed') {
            $query = $query->where('status', Status::ORDER_DELIVERED);
        } elseif ($type == 'canceled') {
            $query = $query->where('status', Status::ORDER_CANCELED);
        }

        $orders = $query->latest()->paginate(getPaginate());

        // Stats
        $userOrders = Order::valid()->where('user_id', auth()->id())->whereNull('remark');
        $stats = [
            'new' => $userOrders->where('status', Status::ORDER_PENDING)->count(),
            'shipping' => $userOrders->whereIn('status', [Status::ORDER_PROCESSING, Status::ORDER_DISPATCHED])->count(),
            'shipped' => $userOrders->where('status', Status::ORDER_READY_TO_DELIVER)->count(),
            'cancelled' => $userOrders->where('status', Status::ORDER_CANCELED)->count(),
            'delivered' => $userOrders->where('status', Status::ORDER_DELIVERED)->count(),
        ];

        return view('seller.purchases.index', compact('pageTitle', 'orders', 'emptyMessage', 'type', 'stats'));
    }

    public function orderDetails($order_number)
    {
        $pageTitle = 'Purchase Details';
        $order = Order::where('order_number', $order_number)->where('user_id', auth()->id())->with('deposit', 'orderDetail', 'appliedCoupon')->firstOrFail();

        return view('seller.purchases.details', compact('order', 'pageTitle'));
    }

    public function productReview()
    {
        $productIds = OrderDetail::whereHas('subOrder', function ($subOrder) {
            $subOrder->whereHas('order', function ($order) {
                $order->where('user_id', auth()->id())->where('status', Status::ORDER_DELIVERED);
            });
        })->pluck('product_id')->toArray();
        $products  =  Product::whereIn('id', $productIds)->with('userReview')->paginate(getPaginate());

        $pageTitle = 'Review Products';
        return view('seller.purchases.reviews', compact('pageTitle', 'products'));
    }

    public function addReview(Request $request)
    {
        $request->validate([
            'pid'       => 'required|string',
            'review'    => 'required|string',
            'rating'    => 'required|numeric',
        ]);

        $user = auth()->user();

        $product = Product::find($request->pid);
        if (!$product) {
            $notify[] = ['error', 'Product not found'];
            return back()->withNotify($notify);
        }

        // check user has purchased this product or not
        $checkOrder =  OrderDetail::whereHas('subOrder', function ($subOrder) {
            $subOrder->whereHas('order', function ($order) {
                $order->where('user_id', auth()->id())->where('status', Status::ORDER_DELIVERED);
            });
        })->where('product_id', $product->id)->exists();

        if (!$checkOrder) {
            $notify[] = ['error', 'You have to purchase this product before review'];
            return back()->withNotify($notify);
        }

        $alreadyReviewed = ProductReview::where('user_id', $user->id)->where('product_id', $request->pid)->exists();

        if ($alreadyReviewed) {
            $notify[] = ['error', 'You have already reviewed this product'];
            return back()->withNotify($notify);
        }

        $productReview = new ProductReview();
        $productReview->user_id = $user->id;
        $productReview->product_id = $request->pid;
        $productReview->review = $request->review;
        $productReview->rating = $request->rating;
        $productReview->save();

        $notify[] = ['success', 'Review added successfully'];
        return back()->withNotify($notify);
    }
}
