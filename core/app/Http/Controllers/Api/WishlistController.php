<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    public function wishlist()
    {
        $user = auth()->user();
        $wishlist = Wishlist::where('user_id', $user->id)
            ->with(['product', 'product.stocks', 'product.categories', 'product.brand'])
            ->whereHas('product', function ($q) {
                return $q->whereHas('categories')->whereHas('brand');
            })
            ->latest()
            ->paginate(getPaginate());

        $notify[] = 'Wishlist data';
        return response()->json([
            'remark' => 'wishlist',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'wishlist' => $wishlist
            ]
        ]);
    }

    public function addToWishlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'remark' => 'validation_error',
                'status' => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $user = auth()->user();

        $wishlist = Wishlist::where('user_id', $user->id)->where('product_id', $request->product_id)->first();

        if ($wishlist) {
            $notify[] = 'Sản phẩm đã có trong danh sách yêu thích';
            return response()->json([
                'remark' => 'already_exists',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $wishlist = new Wishlist();
        $wishlist->user_id    = $user->id;
        $wishlist->product_id = $request->product_id;
        $wishlist->save();

        $notify[] = 'Đã thêm vào danh sách yêu thích';
        return response()->json([
            'remark' => 'added_to_wishlist',
            'status' => 'success',
            'message' => ['success' => $notify],
        ]);
    }

    public function removeFromWishlist($id)
    {
        $user = auth()->user();
        $wishlist = Wishlist::where('user_id', $user->id)->find($id);

        if (!$wishlist) {
            $notify[] = 'Không tìm thấy sản phẩm trong danh sách';
            return response()->json([
                'remark' => 'not_found',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $wishlist->delete();

        $notify[] = 'Đã xóa khỏi danh sách yêu thích';
        return response()->json([
            'remark' => 'removed_from_wishlist',
            'status' => 'success',
            'message' => ['success' => $notify],
        ]);
    }
}
