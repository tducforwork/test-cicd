<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class SellerWishlistController extends Controller
{
    public function index()
    {
        $pageTitle = 'Quản lý Wishlist';

        $products = Product::where('user_id', auth()->id())
            ->whereHas('wishlists')
            ->with(['wishlists', 'stocks', 'categories', 'province'])
            ->latest()
            ->paginate(getPaginate());

        return view('seller.wishlist.index', compact('pageTitle', 'products'));
    }

    public function remove(Request $request, $id)
    {
        $product = Product::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$product) {
            $notify[] = ['error', 'Sản phẩm không tồn tại hoặc không thuộc quyền sở hữu của bạn'];
            return back()->withNotify($notify);
        }

        $count = Wishlist::where('product_id', $id)->count();
        Wishlist::where('product_id', $id)->delete();

        $notify[] = ['success', "Đã xóa $count wishlist khỏi sản phẩm này"];
        return back()->withNotify($notify);
    }
}
