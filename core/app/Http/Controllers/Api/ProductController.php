<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\OrderDetail;
use App\Constants\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::publishable();

        if ($request->search) {
            $products->where(function ($q) use ($request) {
                $q->where('name', 'like', "%$request->search%")
                    ->orWhere('summary', 'like', "%$request->search%");
            });
        }

        if ($request->category_id) {
            $products->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        if ($request->brand_id) {
            $products->whereHas('brand', function ($q) use ($request) {
                $q->where('brands.id', $request->brand_id);
            });
        }

        if ($request->brands && is_array($request->brands)) {
            $products->whereHas('brand', function ($q) use ($request) {
                $q->whereIn('brands.id', $request->brands);
            });
        }

        if ($request->min_price) {
            $products->where('base_price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $products->where('base_price', '<=', $request->max_price);
        }

        if ($request->is_featured) {
            $products->featured();
        }

        $sort = $request->sort;
        if ($sort == 'price_low') {
            $products->orderBy('base_price', 'asc');
        } elseif ($sort == 'price_high') {
            $products->orderBy('base_price', 'desc');
        } else {
            $products->latest();
        }

        $products = $products->paginate(getPaginate());

        $notify[] = 'Product list';
        return response()->json([
            'remark' => 'product_list',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'products' => $products,
            ]
        ]);
    }

    public function detail($id)
    {
        $product = Product::publishable()->with(['categories', 'brand', 'reviews', 'reviews.user'])->find($id);

        if (!$product) {
            $notify[] = 'Sản phẩm không tồn tại';
            return response()->json([
                'remark' => 'product_not_found',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $relatedProducts = Product::publishable()
            ->where('id', '!=', $id)
            ->whereHas('brand', function ($q) use ($product) {
                $q->whereIn('brands.id', $product->brand->pluck('id'));
            })
            ->limit(10)
            ->get();

        $notify[] = 'Product detail';
        return response()->json([
            'remark' => 'product_detail',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'product' => $product,
                'related_products' => $relatedProducts,
            ]
        ]);
    }

    public function categories()
    {
        $categories = Category::isParent()->with('subcategories')->get();
        $notify[] = 'Category list';
        return response()->json([
            'remark' => 'category_list',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'categories' => $categories,
            ]
        ]);
    }

    public function brands()
    {
        $brands = Brand::active()->get();
        $notify[] = 'Brand list';
        return response()->json([
            'remark' => 'brand_list',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'brands' => $brands,
            ]
        ]);
    }

    public function checkCoupon(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'coupon_code' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'remark' => 'validation_error',
                'status' => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $coupon = \App\Models\Coupon::running()->where('coupon_code', $request->coupon_code)->first();

        if (!$coupon) {
            $notify[] = 'Mã giảm giá không hợp lệ hoặc đã hết hạn';
            return response()->json([
                'remark' => 'invalid_coupon',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $notify[] = 'Mã giảm giá hợp lệ';
        return response()->json([
            'remark' => 'coupon_valid',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'coupon' => $coupon
            ]
        ]);
    }
}
