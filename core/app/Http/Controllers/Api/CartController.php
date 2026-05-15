<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AssignProductAttribute;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductStock;
use App\Traits\CartManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    use CartManager;

    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
            'quantity'  => 'required|numeric|gt:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'remark' => 'validation_error',
                'status' => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $product = Product::find($request->product_id);
        if (!$product) {
            $notify[] = 'Sản phẩm không tồn tại';
            return response()->json([
                'remark' => 'product_not_found',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $userid = auth()->id();
        $selectedAttributes = $request->attributes; // Expecting array of attribute IDs

        if ($selectedAttributes != null) {
            sort($selectedAttributes);
            $selectedAttributes = json_encode($selectedAttributes);
        }

        // Check Stock
        if ($product->track_inventory) {
            $stock = ProductStock::showAvailableStock($request->product_id, $selectedAttributes);
            $stock_qty = $stock->quantity ?? 0;
            if ($request->quantity > $stock_qty) {
                $notify[] = 'Số lượng vượt quá tồn kho';
                return response()->json([
                    'remark' => 'out_of_stock',
                    'status' => 'error',
                    'message' => ['error' => $notify],
                ]);
            }
        }

        $cart = Cart::where('user_id', $userid)->where('product_id', $request->product_id)->where('attributes', $selectedAttributes)->first();

        if ($cart) {
            $cart->quantity += $request->quantity;
            $cart->save();
        } else {
            $cart = new Cart();
            $cart->user_id    = $userid;
            $cart->seller_id  = $product->seller_id;
            $cart->attributes = json_decode($selectedAttributes);
            $cart->product_id = $request->product_id;
            $cart->quantity   = $request->quantity;
            $cart->save();
        }

        $notify[] = 'Đã thêm vào giỏ hàng';
        return response()->json([
            'remark' => 'added_to_cart',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'cart' => $cart
            ]
        ]);
    }

    public function getCart()
    {
        $carts = $this->getItems();
        $subtotal = $this->getCartSubTotal($carts);

        $notify[] = 'Cart items';
        return response()->json([
            'remark' => 'cart_items',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'carts' => $carts,
                'subtotal' => $subtotal,
            ]
        ]);
    }

    public function updateCartItem(Request $request, $id)
    {
        $cart_item = Cart::where('id', $id)->where('user_id', auth()->id())->first();

        if (!$cart_item) {
            $notify[] = 'Không tìm thấy sản phẩm trong giỏ hàng';
            return response()->json([
                'remark' => 'cart_item_not_found',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        if ($request->quantity <= 0) {
            $cart_item->delete();
            $notify[] = 'Đã xóa sản phẩm khỏi giỏ hàng';
            return response()->json([
                'remark' => 'cart_item_removed',
                'status' => 'success',
                'message' => ['success' => $notify],
            ]);
        }

        $cart_item->quantity = $request->quantity;
        $cart_item->save();

        $notify[] = 'Đã cập nhật số lượng';
        return response()->json([
            'remark' => 'cart_item_updated',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'cart' => $cart_item
            ]
        ]);
    }

    public function removeCartItem($id)
    {
        $cart = Cart::where('id', $id)->where('user_id', auth()->id())->first();
        if (!$cart) {
            $notify[] = 'Không tìm thấy sản phẩm';
            return response()->json([
                'remark' => 'cart_item_not_found',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $cart->delete();
        $notify[] = 'Đã xóa sản phẩm khỏi giỏ hàng';
        return response()->json([
            'remark' => 'cart_item_deleted',
            'status' => 'success',
            'message' => ['success' => $notify],
        ]);
    }
}
