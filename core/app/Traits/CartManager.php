<?php

namespace App\Traits;

use App\Models\AssignProductAttribute;
use App\Models\Cart;

trait CartManager
{

    public function getItems()
    {
        return Cart::where('user_id', auth()->id())->orWhere('session_id', session('session_id'))->with(['product' => function ($q) {
            return $q->active();
        }, 'product.categories'])->get();
    }

    public function getCartSubTotal($carts = null)
    {
        $carts = $carts ?? $this->getItems();

        $subtotal = 0;
        foreach ($carts as $cart) {
            $product = $cart->product;
            if (!empty($cart->attributes)) {
                $price = AssignProductAttribute::priceAfterAttribute($product, $cart->attributes);
            } else {
                $price = ($product->discount_price > 0) ? $product->discount_price : $product->base_price;
            }
            $subtotal += $price * $cart->quantity;
        }

        return $subtotal;
    }
}
