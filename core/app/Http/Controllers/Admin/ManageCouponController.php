<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ManageCouponController extends Controller
{
    public function index()
    {
        $pageTitle     = __("All Coupons");
        $coupons       = Coupon::with('categories')->get();
        return view('admin.coupons.index', compact('pageTitle', 'coupons'));
    }

    public function create()
    {
        $pageTitle     = __("Create New Coupon");
        $categories    = Category::where('parent_id', null)->with('allSubcategories')->get();
        $sellers       = \App\Models\User::where('is_seller', 1)->get();
        return view('admin.coupons.create', compact('pageTitle', 'categories', 'sellers'));
    }

    public function save(Request $request, $id)
    {
        $rules = [
            "coupon_name"               => 'required|string|max:50',
            "coupon_code"               => 'required|string|max:20',
            "seller_id"                 => 'nullable|integer',
            "categories"                => 'array',
            "products"                  => 'array',
            "discount_type"             => 'required|integer|between:1,2',
            "amount"                    => 'required|numeric|min:0',
            "start_date"                => 'required|date|date_format:Y-m-d',
            "end_date"                  => 'required|date|date_format:Y-m-d',
            "minimum_spend"             => 'nullable|numeric',
            "maximum_spend"             => 'nullable|numeric',
            "usage_limit_per_coupon"    => 'nullable|integer',
            "usage_limit_per_customer"  => 'nullable|integer'
        ];

        $request->validate($rules);

        if ($id == 0) {
            $coupon = new Coupon();
            $notify[] = ['success', __('Coupon created successfully')];
        } else {
            $coupon = Coupon::findOrFail($id);
            $notify[] = ['success', __('Coupon updated successfully')];
        }

        $coupon->seller_id              = $request->seller_id ?? 0;
        $coupon->coupon_name            = $request->coupon_name;
        $coupon->coupon_code            = $request->coupon_code;
        $coupon->discount_type          = $request->discount_type;
        $coupon->coupon_amount          = $request->amount;
        $coupon->description            = $request->description;
        $coupon->start_date             = $request->start_date;
        $coupon->end_date               = $request->end_date;
        $coupon->minimum_spend          = $request->minimum_spend;
        $coupon->maximum_spend          = $request->maximum_spend;
        $coupon->usage_limit_per_coupon = $request->usage_limit_per_coupon;
        $coupon->usage_limit_per_user   = $request->usage_limit_per_customer;

        $coupon->save();

        if ($id != 0) {
            $coupon->categories()->sync($request->categories);
            $coupon->products()->sync($request->products);
        } else {
            $coupon->categories()->attach($request->categories);
            $coupon->products()->attach($request->products);
        }

        return back()->withNotify($notify);
    }

    public function edit($id)
    {
        $coupon         = Coupon::whereId($id)->with(['categories', 'products' => function ($q) {
            return $q->whereHas('brand')->whereHas('categories');
        }])->firstOrFail();
        $pageTitle     = __("Edit Coupon");
        $categories     = Category::with('allSubcategories')->where('parent_id', null)->get();
        $sellers       = \App\Models\User::where('is_seller', 1)->get();
        return view('admin.coupons.create', compact('pageTitle', 'categories', 'coupon', 'sellers'));
    }

    public function delete($id)
    {
        $coupon = Coupon::where('id', $id)->first();
        $coupon->categories()->detach();
        $coupon->delete();
        $notify[] = ['success', __('Coupon deleted successfully')];
        return back()->withNotify($notify);
    }


    public function products(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $products = Product::select('id', 'name')->where('name', 'like', "%$request->search%")->whereHas('categories')->whereHas('brand')->paginate($request->rows ?? 5);

        $response = [];

        foreach ($products as $product) {
            $response[] = [
                "id"    => $product->id,
                "text"  => $product->name
            ];
        }

        return response()->json($response);
    }


    public function changeStatus(Request $request)
    {
        $coupon = Coupon::findOrFail($request->id);
        if ($coupon) {
            if ($coupon->status == 1) {
                $coupon->status = 0;
                $msg = __('Coupon deactivated successfully');
            } else {
                $coupon->status = 1;
                $msg = __('Coupon activated successfully');
            }
            $coupon->save();
            return response()->json(['success' => $msg]);
        }
    }
}
