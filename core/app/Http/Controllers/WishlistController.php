<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    public function addToWishList(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'status' => false,
                'message' => __('Please login to add to wishlist')
            ]);
        }

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }

        $userid = auth()->id();

        $wishlist = Wishlist::where('user_id', $userid)
            ->where('product_id', $request->product_id)->first();

        if ($wishlist) {
            return response()->json([
                'status' => false,
                'message' => __('Already in the wish list')
            ]);
        }

        $wishlist = new Wishlist();
        $wishlist->user_id    = $userid;
        $wishlist->product_id = $request->product_id;
        $wishlist->save();

        return response()->json([
            'status' => true,
            'message' => __('Added to Wishlist')
        ]);
    }


    public function getWishListTotal()
    {
        $userid = auth()->id();
        if (!$userid) {
            return response(0);
        }

        $totalWishlist = Wishlist::where('user_id', $userid)
            ->whereHas('product', function ($q) {
                return $q->publishable();
            })->count();

        return response($totalWishlist);
    }

    public function wishList()
    {
        $userid = auth()->id();
        $pageTitle = 'Danh sách yêu thích';
        
        $wishlist_data = Wishlist::where('user_id', $userid)
            ->with(['product', 'product.stocks', 'product.categories', 'product.offer'])
            ->whereHas('product', function ($q) {
                return $q->publishable();
            })
            ->get();

        $wishlistProducts = $wishlist_data;

        $emptyMessage = 'No product in your wishlist';
        
        // Check which route is being called
        $routeName = request()->route()->getName();
        
        if ($routeName == 'danh-sach-yeu-thich') {
            return view(activeTemplate() . 'wishlist', compact('pageTitle', 'wishlistProducts', 'emptyMessage'));
        }
        
        return view(activeTemplate() . 'user.wishlist', compact('pageTitle', 'wishlistProducts', 'emptyMessage'));
    }

    public function removeFromWishList($id = 0)
    {
        if ($id) {
            $wishlist = Wishlist::where(function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhere('session_id', session()->get('session_id'));
            })->find($id);

            if (!$wishlist) {
                return response()->json([
                    'status' => false,
                    'message' => 'This product isn\'t available in your wishlist'
                ]);
            }

            $sessionWishlist = session()->get('wishlist');
            unset($sessionWishlist[$wishlist->product_id]);
            session()->put('wishlist', $sessionWishlist);
            $wishlist->delete();

            $message = 'The product has been removed successfully';
        } else {
            Wishlist::where(function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhere('session_id', session()->get('session_id'));
            })->delete();

            session()->forget('wishlist');
            $message = 'Wishlist cleared successfully';
        }

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
}
