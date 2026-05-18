<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Coupon;
use App\Models\GeneralSetting;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function applyCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'          => 'required|string',
            'subtotal'      => 'required|numeric|gt:0',
            'categories'    => 'nullable|array|'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        $now = Carbon::now();

        $coupon = Coupon::where('coupon_code', $request->code)->with('categories')->where('start_date', '<=', $now)->where('end_date', '>=', $now)->where('status', 1)->with(['appliedCoupons', 'categories', 'products'])->first();

        if($coupon){

            // Check Minimum Subtotal
            if($request->subtotal < $coupon->minimum_spend){
                return response()->json(['error' => "Đơn hàng của bạn phải đạt tối thiểu "  . showAmount($coupon->minimum_spend)]);
            }

            // Check Maximum Subtotal
            if($coupon->maximum_spend > 0 && $request->subtotal > $coupon->maximum_spend){
                return response()->json(['error' => "Mã này chỉ áp dụng cho đơn hàng tối đa " . showAmount($coupon->maximum_spend)]);
            }

            //Check Limit Per Coupon
            if($coupon->appliedCoupons->count() >= $coupon->usage_limit_per_coupon){
                return response()->json(['error' => "Mã giảm giá này đã hết lượt sử dụng."]);
            }

            //Check Limit Per User
            if($coupon->appliedCoupons->where('user_id', auth()->id())->count() >= $coupon->usage_limit_per_user){
                return response()->json(['error' => "Bạn đã đạt giới hạn sử dụng tối đa cho mã giảm giá này."]);
            }

            // Hiện tại áp dụng mã giảm giá cho toàn bộ giỏ hàng, không giới hạn theo từng sản phẩm hoặc danh mục cụ thể


            if($coupon->discount_type == 1){
                $amount = $coupon->coupon_amount;
            }else{
                $amount = $request->subtotal * $coupon->coupon_amount / 100;
            }

            // Check in session

            if(session()->has('coupon') && session('coupon')['code'] == $request->code){
                return response()->json(['error' => 'Mã giảm giá này đã được áp dụng.']);
            }


            session()->put('coupon', ['code'=>$request->code,'amount' => $amount]);

            return response()->json([
                'success' => 'Áp dụng mã giảm giá thành công!',
                'coupon_code'    => $coupon->coupon_code,
                'amount'  => $amount
            ]);
        }else{
            return response()->json(['error' => 'Mã giảm giá không tồn tại hoặc đã hết hạn.']);
        }
    }

    public function removeCoupon()
    {
        session()->forget('coupon');
        return response()->json(['success'=>'Đã gỡ mã giảm giá thành công.']);
    }

    public function getCoupon($code)
    {
        $coupon = Coupon::where('coupon_code', $code)->where('status', 1)->first();
        if(!$coupon){
            return response()->json(['error' => 'Mã giảm giá không tồn tại hoặc đã hết hạn']);
        }
        return response()->json([
            'success' => true,
            'coupon' => $coupon,
            'formatted_end_date' => Carbon::parse($coupon->end_date)->format('d/m/Y')
        ]);
    }
}
