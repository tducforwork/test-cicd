<?php

namespace App\Http\Controllers\Api;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ShippingMethod;
use App\Models\Province;
use App\Models\Ward;
use App\Models\Coupon;
use App\Models\Deposit;
use App\Models\SubOrder;
use App\Models\StockLog;
use App\Models\AppliedCoupon;
use App\Models\AssignProductAttribute;
use App\Traits\CartManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    use CartManager;

    public function list(Request $request)
    {
        $type = $request->type;
        $query = Order::where('user_id', auth()->id());

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

        $notify[] = 'Order list';
        return response()->json([
            'remark' => 'order_list',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'orders' => $orders,
            ]
        ]);
    }

    public function detail($id)
    {
        $order = Order::where('id', $id)->where('user_id', auth()->id())->with(['deposit', 'orderDetail', 'orderDetail.product', 'appliedCoupon'])->first();

        if (!$order) {
            $notify[] = 'Không tìm thấy đơn hàng';
            return response()->json([
                'remark' => 'order_not_found',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $notify[] = 'Order details';
        return response()->json([
            'remark' => 'order_detail',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'order' => $order,
            ]
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname'         => 'required',
            'lastname'          => 'required',
            'mobile'            => 'required',
            'email'             => 'required|email',
            'address'           => 'required',
            'province_id'       => 'required|integer',
            'ward_id'           => 'required|integer',
            'payment'           => 'required|in:1,2' // 1: Online, 2: COD
        ]);

        if ($validator->fails()) {
            return response()->json([
                'remark' => 'validation_error',
                'status' => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        if (!gs('cod') && $request->payment == Status::COD) {
            $notify[] = 'Thanh toán khi nhận hàng hiện không khả dụng';
            return response()->json([
                'remark' => 'cod_not_available',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $cartData = $this->getItems(); // This usually gets items from the Cart model for the auth user

        if ($cartData->isEmpty()) {
            $notify[] = 'Giỏ hàng của bạn đang trống';
            return response()->json([
                'remark' => 'cart_empty',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $user = auth()->user();
        $general = gs();
        $couponAmount      = 0;
        $couponCode        = null;
        $cartTotal         = 0;
        $productCategories = [];

        foreach ($cartData as $cart) {
            $productCategories[] = $cart->product->categories->pluck('id')->toArray();

            if ($cart->product->offer && $cart->product->offer->activeOffer) {
                $offerAmount = calculateDiscount($cart->product->offer->activeOffer->amount, $cart->product->offer->activeOffer->discount_type, $cart->product->base_price);
            } else {
                $offerAmount = 0;
            }

            if ($cart->attributes != null) {
                $attr_item                   = AssignProductAttribute::productAttributesDetails($cart->attributes);
                $attr_item['offer_amount'] = $offerAmount;
                $subtotal                   = (($cart->product->base_price + $attr_item['extra_price']) - $offerAmount) * $cart->quantity;
            } else {
                $subtotal                  = ($cart->product->base_price  - $offerAmount) * $cart->quantity;
            }
            $cartTotal += $subtotal;
        }

        $productCategories = array_unique(array_merge(...$productCategories));

        // Note: Coupon logic here assumes coupon info might be in request or session. 
        // For API, it's better to pass coupon code in request.
        if ($request->coupon) {
            $coupon = Coupon::running()->where('coupon_code', $request->coupon)->with('categories')->first();
            if ($coupon) {
                // Validation logic (simplified)
                if ($coupon->discount_type == 1) {
                    $couponAmount = $cartTotal > $coupon->coupon_amount ? $coupon->coupon_amount : $cartTotal;
                } else {
                    $couponAmount = ($cartTotal * $coupon->coupon_amount) / 100;
                }
                $couponCode = $coupon->coupon_code;
            }
        }

        $province = Province::find($request->province_id);
        $ward     = Ward::find($request->ward_id);

        $shippingAddress   = [
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'mobile'    => $request->mobile,
            'province'  => $province ? $province->full_name : '',
            'ward'      => $ward ? $ward->full_name : '',
            'address'   => $request->address,
        ];

        $order                      = new Order();
        $order->order_number        = getTrx();
        $order->user_id             = $user->id;
        $order->shipping_address    = json_encode($shippingAddress);
        $order->shipping_method_id  = 0;
        $order->shipping_charge     = 0;
        $order->order_type          = 1; // Default
        $order->payment_status      = $request->payment == Status::COD ? Status::COD : Status::PAYMENT_INITIATE;
        $order->save();

        foreach ($cartData->groupBy('product.seller_id') as $key => $sellerCarts) {
            $suborderTotal = 0;
            $suborder = new SubOrder();
            $suborder->order_id = $order->id;
            $suborder->seller_id = $key;
            $suborder->order_number = getTrx();
            $suborder->save();

            foreach ($sellerCarts as $cart) {
                $orderDetail                  = new OrderDetail();
                $orderDetail->sub_order_id    = $suborder->id;
                $orderDetail->product_id      = $cart->product_id;
                $orderDetail->quantity        = $cart->quantity;
                $orderDetail->base_price      = $cart->product->base_price;

                $offerAmount = 0;
                if ($cart->product->offer && $cart->product->offer->activeOffer) {
                    $offerAmount = calculateDiscount($cart->product->offer->activeOffer->amount, $cart->product->offer->activeOffer->discount_type, $cart->product->base_price);
                }

                if ($cart->attributes != null) {
                    $attr_item                            = AssignProductAttribute::productAttributesDetails($cart->attributes);
                    $attr_item['offer_amount']            = $offerAmount;
                    $subtotal                             = (($cart->product->base_price + $attr_item['extra_price']) - $offerAmount) * $cart->quantity;
                    $orderDetail->total_price             = $subtotal;
                    $orderDetail->details                 = json_encode($attr_item);
                    $orderDetail->product_attributes      = json_encode($cart->attributes);
                } else {
                    $details['variants']        = null;
                    $details['offer_amount']    = $offerAmount;
                    $subtotal                   = ($cart->product->base_price  - $offerAmount) * $cart->quantity;
                    $orderDetail->total_price   = $subtotal;
                    $orderDetail->details       = json_encode($details);
                }

                $orderDetail->save();
                $suborderTotal += $subtotal;
            }

            $suborder->total_amount = $suborderTotal;
            $suborder->save();
        }

        $order->total_amount =  getAmount($cartTotal - $couponAmount + $order->shipping_charge);
        $order->save();

        if ($couponCode != null) {
            $appliedCoupon = new AppliedCoupon();
            $appliedCoupon->user_id    = $user->id;
            $appliedCoupon->coupon_id  = $coupon->id;
            $appliedCoupon->order_id   = $order->id;
            $appliedCoupon->amount     = $couponAmount;
            $appliedCoupon->save();
        }

        if ($request->payment == 1) {
            // Initiate payment response
            $notify[] = 'Đơn hàng đã được tạo. Vui lòng tiến hành thanh toán.';
            return response()->json([
                'remark' => 'order_created_payment_required',
                'status' => 'success',
                'message' => ['success' => $notify],
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'total_amount' => $order->total_amount,
                ]
            ]);
        } else {
            // COD process
            $deposit = new Deposit();
            $deposit->user_id = $user->id;
            $deposit->method_code        = 0;
            $deposit->order_id           = $order->id;
            $deposit->method_currency    = $general->cur_text;
            $deposit->amount             = $order->total_amount;
            $deposit->charge             = 0;
            $deposit->rate               = 0;
            $deposit->final_amount       = getAmount($order->total_amount);
            $deposit->btc_amount         = 0;
            $deposit->btc_wallet         = "";
            $deposit->trx                = getTrx();
            $deposit->status             = Status::PAYMENT_PENDING;
            $deposit->save();

            StockLog::updateStock($cartData);
            $cartData->each->delete();

            $notify[] = 'Đặt hàng thành công!';
            return response()->json([
                'remark' => 'order_placed',
                'status' => 'success',
                'message' => ['success' => $notify],
                'data' => [
                    'order' => $order
                ]
            ]);
        }
    }
}
