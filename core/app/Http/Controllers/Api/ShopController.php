<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function allSellers()
    {
        $sellers = User::seller()->active()->whereHas('shop')->with('shop')->paginate(getPaginate());
        $notify[] = 'All sellers list';
        return response()->json([
            'remark' => 'all_sellers',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'sellers' => $sellers,
            ]
        ]);
    }

    public function sellerDetails($id)
    {
        $seller = User::seller()->active()->where('id', $id)->whereHas('shop')->with('shop')->first();

        if (!$seller) {
            $notify[] = 'Cửa hàng không tồn tại hoặc đã bị khóa';
            return response()->json([
                'remark' => 'seller_not_found',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $products = Product::publishable()->where('seller_id', $seller->id)->latest()->paginate(20);

        $notify[] = 'Seller details and products';
        return response()->json([
            'remark' => 'seller_details',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'seller' => $seller,
                'products' => $products,
            ]
        ]);
    }

    public function featuredSellers()
    {
        $sellers = User::seller()->active()->featured()->whereHas('shop')->with('shop')->inRandomOrder()->take(10)->get();
        $notify[] = 'Featured sellers';
        return response()->json([
            'remark' => 'featured_sellers',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'sellers' => $sellers,
            ]
        ]);
    }
}
