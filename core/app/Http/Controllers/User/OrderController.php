<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\AppliedCoupon;
use App\Models\AssignProductAttribute;
use App\Models\DeviceToken;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\ShippingMethod;
use App\Models\Province;
use App\Models\Ward;
use App\Models\Coupon;
use App\Models\Deposit;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\StockLog;
use App\Models\SubOrder;
use App\Traits\CartManager;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use CartManager;

    public function orders($type)
    {
        $pageTitle = ucfirst($type) . ' Orders';
        $emptyMessage = 'No order yet';
        $query = Order::valid()->where('user_id', auth()->id())->whereNull('remark');

        // Filter dựa trên SubOrder status (computed status)
        if ($type == 'pending') {
            $orderIds = $this->getOrderIdsBySubOrderStatus([Status::SUBORDER_PENDING]);
            $query = $query->whereIn('id', $orderIds);
        } elseif ($type == 'processing') {
            $orderIds = $this->getOrderIdsBySubOrderStatus([Status::SUBORDER_PROCESSING, Status::SUBORDER_READY_TO_PICKUP]);
            $query = $query->whereIn('id', $orderIds);
        } elseif ($type == 'dispatched') {
            $orderIds = $this->getOrderIdsBySubOrderStatus([Status::SUBORDER_DISPATCHED]);
            $query = $query->whereIn('id', $orderIds);
        } elseif ($type == 'completed') {
            $orderIds = $this->getOrderIdsBySubOrderStatus([Status::SUBORDER_DELIVERED, Status::SUBORDER_COMPLETED]);
            $query = $query->whereIn('id', $orderIds);
        } elseif ($type == 'canceled') {
            $orderIds = $this->getOrderIdsBySubOrderStatus([Status::SUBORDER_REJECTED], true);
            $query = $query->whereIn('id', $orderIds);
        }

        $orders = $query->with(['subOrders.seller:id,username,firstname,lastname', 'subOrders.orderDetail.product:id,name,main_image'])
            ->latest()
            ->paginate(getPaginate());

        // Stats dựa trên SubOrder
        $stats = $this->getOrderStats();

        return view('Template::user.orders.index', compact('pageTitle', 'orders', 'emptyMessage', 'type', 'stats'));
    }

    private function getOrderIdsBySubOrderStatus(array $statuses, $allRejected = false)
    {
        $subQuery = SubOrder::whereIn('status', $statuses);

        if ($allRejected) {
            return SubOrder::whereIn('status', [Status::SUBORDER_REJECTED])
                ->pluck('order_id')
                ->toArray();
        }

        return $subQuery->pluck('order_id')->toArray();
    }

    private function getOrderStats()
    {
        $userOrderIds = Order::valid()
            ->where('user_id', auth()->id())
            ->whereNull('remark')
            ->pluck('id')
            ->toArray();

        $subOrders = SubOrder::whereIn('order_id', $userOrderIds)->get();

        return [
            'new' => $subOrders->where('status', Status::SUBORDER_PENDING)->count(),
            'shipping' => $subOrders->whereIn('status', [
                Status::SUBORDER_PROCESSING,
                Status::SUBORDER_READY_TO_PICKUP,
                Status::SUBORDER_DISPATCHED
            ])->count(),
            'shipped' => 0, // Not used anymore
            'cancelled' => $subOrders->where('status', Status::SUBORDER_REJECTED)->count(),
            'delivered' => $subOrders->whereIn('status', [
                Status::SUBORDER_DELIVERED,
                Status::SUBORDER_COMPLETED
            ])->count(),
        ];
    }

    public function orderDetails($order_number)
    {
        $pageTitle = 'Order Details';
        $order = Order::where('order_number', $order_number)
            ->where('user_id', auth()->id())
            ->with([
                'deposit',
                'subOrders' => function ($query) {
                    $query->with(['seller:id,username,firstname,lastname', 'orderDetail.product'])
                        ->orderBy('id', 'asc');
                },
                'appliedCoupon'
            ])
            ->first();

        return view('Template::user.orders.details', compact('order', 'pageTitle'));
    }

    public function confirmOrder(Request $request, $type)
    {
        // 1. Validate đơn hàng cơ bản
        $this->validateOrderRequest($request);

        if (!gs('cod') && $request->payment == Status::COD) {
            $notify[] = ['error', 'Thanh toán COD hiện tại không khả dụng.'];
            return back()->withNotify($notify);
        }

        // 2. Lấy giỏ hàng
        $cartData = $this->getItems();
        if ($cartData->isEmpty()) {
            $notify[] = ['error', 'Giỏ hàng của bạn hiện đang trống.'];
            return back()->withNotify($notify);
        }

        // 3. Tính toán tổng tiền và coupon
        $cartTotal = $this->calculateCartTotal($cartData);
        $couponData = $this->handleCouponLogic($cartData, $cartTotal);

        if (isset($couponData['error_json'])) {
            if ($request->ajax()) {
                return response()->json(['error' => $couponData['error_json']]);
            }
            $notify[] = ['error', $couponData['error_json']];
            return back()->withNotify($notify)->withInput();
        }
        if (isset($couponData['error_notify'])) {
            if ($request->ajax()) {
                $message = is_array($couponData['error_notify']) ? @$couponData['error_notify'][0][1] : $couponData['error_notify'];
                return response()->json(['error' => $message]);
            }
            return back()->withNotify($couponData['error_notify']);
        }

        // 4. Tạo các bản ghi đơn hàng (DB Transaction)
        try {
            $order = $this->createOrderRecords($request, $type, $cartData, $cartTotal, $couponData);
        } catch (\Exception $e) {
            $notify[] = ['error', 'Đã xảy ra lỗi trong quá trình xác nhận đơn hàng: ' . $e->getMessage()];
            return back()->withNotify($notify);
        }

        session()->put('order_number', $order->order_number);

        // 5. Điều hướng theo phương thức thanh toán
        if ($request->payment == 1) {
            return $this->handleOnlinePayment($order);
        }

        return $this->handleCodPayment($order, $cartData);
    }

    private function validateOrderRequest(Request $request)
    {
        return $request->validate([
            'firstname'   => 'required',
            'lastname'    => 'required',
            'mobile'      => 'required',
            'email'       => 'required|email',
            'address'     => 'required',
            'province_id' => 'required|integer',
            'ward_id'     => 'required|integer',
            'payment'     => 'required|in:1,2',
            'note'        => 'nullable|string'
        ]);
    }

    private function calculateCartTotal($cartData)
    {
        $cartTotal = 0;
        foreach ($cartData as $cart) {
            $cartTotal += AssignProductAttribute::priceAfterAttribute($cart->product, $cart->attributes) * $cart->quantity;
        }
        return $cartTotal;
    }

    private function handleCouponLogic($cartData, $cartTotal)
    {
        $result = [
            'amount' => 0,
            'code' => null,
            'coupon' => null
        ];

        if (!session('coupon')) return $result;

        $general = gs();
        $user = auth()->user();
        $coupon = Coupon::running()->where('coupon_code', session('coupon')['code'])->with(['categories', 'products'])->first();

        if (!$coupon) return $result;

        // Determine eligible products for this coupon
        // Rule: Products in a promotion are NOT eligible for coupons
        $eligibleCarts = $cartData->filter(function ($cart) use ($coupon) {
            // Check if product has an active promotion
            if ($cart->product->active_promotion) {
                return false;
            }

            // If it's a seller coupon, must match seller_id
            if ($coupon->seller_id > 0 && $cart->product->seller_id != $coupon->seller_id) {
                return false;
            }

            return true;
        });

        if ($eligibleCarts->isEmpty()) {
            return ['error_notify' => [['error', 'Mã giảm giá này không áp dụng cho các sản phẩm trong giỏ hàng của bạn (Sản phẩm đang khuyến mãi không được áp dụng)']]];
        }

        $eligibleTotal = 0;
        foreach ($eligibleCarts as $cart) {
            $eligibleTotal += AssignProductAttribute::priceAfterAttribute($cart->product, $cart->attributes) * $cart->quantity;
        }

        // Check Minimum Subtotal (Based on eligible items)
        if ($eligibleTotal < $coupon->minimum_spend) {
            return ['error_json' => "Đơn hàng của bạn phải đạt tối thiểu " . showAmount($coupon->minimum_spend) . " đối với các sản phẩm hợp lệ để sử dụng mã giảm giá này."];
        }

        if ($coupon->maximum_spend != null && $eligibleTotal > $coupon->maximum_spend) {
            return ['error_json' => "Mã giảm giá này chỉ áp dụng cho các sản phẩm hợp lệ có tổng giá trị tối đa " . showAmount($coupon->maximum_spend)];
        }

        // Check Limit Per Coupon
        if ($coupon->appliedCoupons->count() >= $coupon->usage_limit_per_coupon) {
            return ['error_json' => "Rất tiếc, mã giảm giá này đã vượt quá giới hạn sử dụng tối đa."];
        }

        // Check Limit Per User
        if ($coupon->appliedCoupons->where('user_id', $user->id)->count() >= $coupon->usage_limit_per_user) {
            return ['error_json' => "Rất tiếc, bạn đã đạt giới hạn sử dụng tối đa cho mã giảm giá này."];
        }

        // Check Categories/Products restriction (Standard logic)
        $couponCategories = $coupon->categories->pluck('id')->toArray();
        $couponProducts = $coupon->products->pluck('id')->toArray();

        if (!empty($couponCategories) || !empty($couponProducts)) {
            $validForCart = false;
            foreach ($eligibleCarts as $cart) {
                $pId = $cart->product_id;
                $pCats = $cart->product->categories->pluck('id')->toArray();

                if (in_array($pId, $couponProducts) || !empty(array_intersect($pCats, $couponCategories))) {
                    $validForCart = true;
                    break;
                }
            }

            if (!$validForCart) {
                return ['error_notify' => [['error', 'Mã giảm giá này không khả dụng cho bất kỳ sản phẩm hợp lệ nào trong giỏ hàng của bạn.']]];
            }
        }

        // Calculate discount amount
        $result['amount'] = ($coupon->discount_type == 1)
            ? ($eligibleTotal > $coupon->coupon_amount ? $coupon->coupon_amount : $eligibleTotal)
            : ($eligibleTotal * $coupon->coupon_amount) / 100;

        $result['code'] = $coupon->coupon_code;
        $result['coupon'] = $coupon;

        return $result;
    }

    private function createOrderRecords($request, $type, $cartData, $cartTotal, $couponData)
    {
        return DB::transaction(function () use ($request, $type, $cartData, $cartTotal, $couponData) {
            $user = auth()->user();
            $province = Province::find($request->province_id);
            $ward = Ward::find($request->ward_id);

            $shippingAddress = [
                'firstname' => $request->firstname,
                'lastname'  => $request->lastname,
                'mobile'    => $request->mobile,
                'province'  => $province ? $province->full_name : '',
                'ward'      => $ward ? $ward->full_name : '',
                'address'   => $request->address,
            ];

            // 1. Tạo đơn hàng chính
            $order = new Order();
            $order->order_number        = getTrx();
            $order->user_id             = $user->id;
            $order->shipping_address    = json_encode($shippingAddress);
            $order->shipping_method_id  = 0;
            $order->shipping_charge     = 0;
            $order->order_type          = $type;
            $order->payment_status      = $request->payment == Status::COD ? Status::COD : Status::PAYMENT_INITIATE;
            $order->note                = $request->note;
            $order->save();

            // 2. Tạo SubOrders & OrderDetails
            $totalShippingCharge = 0;
            foreach ($cartData->groupBy('seller_id') as $sellerId => $sellerCarts) {
                $suborder = new SubOrder();
                $suborder->order_id = $order->id;
                $suborder->seller_id = $sellerId;
                $suborder->order_number = getTrx();
                $suborder->shipping_charge = 30000; // Flat rate of 30,000 VND per Shop/Seller
                $suborder->save();

                $totalShippingCharge += 30000;

                $suborderTotal = 0;
                foreach ($sellerCarts as $cart) {
                    $subtotal = $this->createOrderDetail($suborder, $cart);
                    $suborderTotal += $subtotal;
                }

                // If this is a seller-specific coupon, subtract it from the seller's suborder total
                if ($couponData['coupon'] && $couponData['coupon']->seller_id == $sellerId) {
                    $suborderTotal = max(0, $suborderTotal - $couponData['amount']);
                }

                $suborder->total_amount = $suborderTotal;
                $suborder->save();
            }

            // 3. Cập nhật tổng tiền đơn hàng chính
            $order->shipping_charge = $totalShippingCharge;
            $order->total_amount = getAmount($cartTotal - $couponData['amount'] + $order->shipping_charge);
            $order->save();

            // 4. Áp dụng Coupon nếu có
            if ($couponData['code']) {
                $appliedCoupon = new AppliedCoupon();
                $appliedCoupon->user_id    = $user->id;
                $appliedCoupon->coupon_id  = $couponData['coupon']->id;
                $appliedCoupon->order_id   = $order->id;
                $appliedCoupon->amount     = $couponData['amount'];
                $appliedCoupon->save();
                session()->forget('coupon');
            }

            return $order;
        });
    }

    private function createOrderDetail($suborder, $cart)
    {
        $orderDetail               = new OrderDetail();
        $orderDetail->sub_order_id = $suborder->id;
        $orderDetail->product_id   = $cart->product_id;
        $orderDetail->quantity     = $cart->quantity;
        $orderDetail->base_price   = $cart->product->final_price;

        if (!empty($cart->attributes)) {
            $attr_item                       = AssignProductAttribute::productAttributesDetails($cart->attributes);
            $attr_item['offer_amount']       = 0;
            $subtotal                        = AssignProductAttribute::priceAfterAttribute($cart->product, $cart->attributes) * $cart->quantity;
            $orderDetail->total_price        = $subtotal;
            unset($attr_item['extra_price']);
            $orderDetail->details            = json_encode($attr_item);
            $orderDetail->product_attributes = json_encode($cart->attributes);
        } else {
            $details['variants']      = null;
            $details['offer_amount']  = 0;
            $subtotal                 = AssignProductAttribute::priceAfterAttribute($cart->product, $cart->attributes) * $cart->quantity;
            $orderDetail->total_price = $subtotal;
            $orderDetail->details     = json_encode($details);
        }

        $orderDetail->save();
        return $subtotal;
    }

    private function handleOnlinePayment($order)
    {
        $general = gs();
        $gate = \App\Models\GatewayCurrency::where('method_code', 512)->where('currency', $general->cur_text)->first();

        if (!$gate) {
            $notify[] = ['error', 'Không tìm thấy cổng thanh toán PayOS hoặc cổng chưa được kích hoạt.'];
            return back()->withNotify($notify);
        }

        $deposit = new Deposit();
        $deposit->user_id         = auth()->id();
        $deposit->order_id        = $order->id;
        $deposit->method_code     = $gate->method_code;
        $deposit->method_currency = strtoupper($gate->currency);
        $deposit->amount          = $order->total_amount;
        $deposit->charge          = 0;
        $deposit->rate            = 1;
        $deposit->final_amount    = getAmount($order->total_amount);
        $deposit->btc_amount      = 0;
        $deposit->btc_wallet      = "";
        $deposit->trx             = getTrx();
        $deposit->success_url     = route('user.thank.you');
        $deposit->failed_url      = route('user.payment.failed');
        $deposit->save();

        session()->put('Track', $deposit->trx);
        return to_route('user.deposit.confirm');
    }

    private function handleCodPayment($order, $cartData)
    {
        $deposit = Deposit::where('user_id', auth()->id())->where('order_id', $order->id)->first() ?? new Deposit();
        $deposit->user_id         = auth()->id();
        $deposit->method_code     = 0;
        $deposit->order_id        = $order->id;
        $deposit->method_currency = gs('cur_text');
        $deposit->amount          = $order->total_amount;
        $deposit->charge          = 0;
        $deposit->rate            = 0;
        $deposit->final_amount    = getAmount($order->total_amount);
        $deposit->btc_amount      = 0;
        $deposit->btc_wallet      = "";
        $deposit->trx             = getTrx();
        $deposit->status          = Status::PAYMENT_PENDING;
        $deposit->save();

        StockLog::updateStock($cartData);
        session()->forget('session_id');
        $cartData->each->delete();

        $order->notifyParties(true);

        $notify[] = ['success', 'Đơn hàng của bạn đã được đặt thành công!'];
        return to_route('user.thank.you')->withNotify($notify);
    }

    public function thankYou()
    {
        $orderNumber = session()->get('order_number');
        if (!$orderNumber) {
            return to_route('user.home');
        }

        $order = Order::where('order_number', $orderNumber)->where('user_id', auth()->id())->first();
        if (!$order) {
            return to_route('user.home');
        }

        $pageTitle = 'Thank You';
        return view('Template::thank_you', compact('pageTitle', 'order'));
    }

    public function paymentFailed()
    {
        $orderNumber = session()->get('order_number');
        if (!$orderNumber) {
            return to_route('user.home');
        }

        $order = Order::where('order_number', $orderNumber)->where('user_id', auth()->id())->first();
        if (!$order) {
            return to_route('user.home');
        }

        $pageTitle = 'Payment Failed';
        return view('Template::payment_failed', compact('pageTitle', 'order'));
    }

    public function productsReview()
    {
        $productIds = OrderDetail::whereHas('subOrder', function ($subOrder) {
            $subOrder->whereHas('order', function ($order) {
                $order->where('user_id', auth()->id())->where('status', Status::ORDER_DELIVERED);
            });
        })->pluck('product_id')->toArray();
        $products  =  Product::whereIn('id', $productIds)->with('userReview')->paginate();

        $pageTitle = 'Review Products';
        return view('Template::user.orders.products_for_review', compact('pageTitle', 'products'));
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
            $notify[] = ['error', 'Không tìm thấy sản phẩm.'];
            return back()->withNotify($notify);
        }

        // check user has purchased this product or not
        $checkOrder =  OrderDetail::whereHas('subOrder', function ($subOrder) {
            $subOrder->whereHas('order', function ($order) {
                $order->where('user_id', auth()->id())->where('status', Status::ORDER_DELIVERED);
            });
        })->where('product_id', $product->id)->exists();

        if (!$checkOrder) {
            $notify[] = ['error', 'Bạn phải mua sản phẩm này trước khi có thể gửi đánh giá.'];
            return back()->withNotify($notify);
        }

        $alreadyReviewed = ProductReview::where('user_id', $user->id)->where('product_id', $request->pid)->exists();

        if ($alreadyReviewed) {
            $notify[] = ['error', 'Bạn đã gửi đánh giá cho sản phẩm này rồi.'];
            return back()->withNotify($notify);
        }

        $productReview = new ProductReview();
        $productReview->user_id = $user->id;
        $productReview->product_id = $request->pid;
        $productReview->review = $request->review;
        $productReview->rating = $request->rating;
        $productReview->save();

        $notify[] = ['success', 'Gửi đánh giá thành công!'];
        return back()->withNotify($notify);
    }

    public function cancelOrder($order_number)
    {
        $order = Order::where('order_number', $order_number)->where('user_id', auth()->id())->with('subOrders')->first();

        if (!$order) {
            $notify[] = ['error', 'Không tìm thấy đơn hàng.'];
            return back()->withNotify($notify);
        }

        // Kiểm tra xem đơn hàng có thể hủy không (Chỉ cho phép khi đang ở trạng thái Chờ xác nhận và chưa thanh toán thành công)
        if ($order->computed_status != Status::ORDER_PENDING || $order->payment_status == Status::PAYMENT_SUCCESS) {
            $notify[] = ['error', 'Bạn không thể hủy đơn hàng này do đơn đã được xử lý, hoàn thành hoặc đã thanh toán.'];
            return back()->withNotify($notify);
        }

        try {
            DB::transaction(function () use ($order) {
                // 1. Cập nhật trạng thái đơn hàng chính
                $order->status = Status::ORDER_CANCELED;
                $order->save();

                // 2. Cập nhật trạng thái và hoàn kho cho từng đơn hàng con
                foreach ($order->subOrders as $subOrder) {
                    if ($subOrder->status != Status::SUBORDER_REJECTED) {
                        $subOrder->status = Status::SUBORDER_REJECTED;
                        $subOrder->save();

                        // Hoàn lại kho
                        StockLog::restoreStock($subOrder->id, true);
                    }
                }
            });

            // 3. Gửi thông báo
            notify($order->user, 'ORDER_CANCELLATION_CONFIRMATION', [
                'site_name' => gs('site_name'),
                'order_id'  => $order->order_number
            ]);

            foreach ($order->subOrders as $subOrder) {
                if ($subOrder->seller_id != 0 && $subOrder->seller) {
                    notify($subOrder->seller, 'SELLER_ORDER_CANCELLED', [
                        'seller_name'     => $subOrder->seller->fullname,
                        'order_number'    => $order->order_number,
                        'suborder_number' => $subOrder->order_number,
                        'site_name'       => gs('site_name'),
                    ]);
                }
            }

            $notify[] = ['success', 'Đơn hàng đã được hủy thành công!'];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            $notify[] = ['error', 'Đã xảy ra lỗi: ' . $e->getMessage()];
            return back()->withNotify($notify);
        }
    }
}
