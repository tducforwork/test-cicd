<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $guarded  = ['id'];
    protected $casts    = ['attributes' => 'array'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function attributes()
    {
        return $this->belongsTo(AssignProductAttribute::class, 'attributes');
    }

    public static function insertUserToCart($user_id, $session_id)
    {
        if (!$user_id || !$session_id) {
            return;
        }

        $sessionCarts = self::where('session_id', $session_id)->get();
        foreach ($sessionCarts as $sessionCart) {

            // BYPASS ELOQUENT INTERNAL $attributes conflict and relationships
            $attributesQuery = $sessionCart->getRawOriginal('attributes');

            $userCartQuery = self::where('user_id', $user_id)->where('product_id', $sessionCart->product_id);

            if ($attributesQuery === null || $attributesQuery === '[]' || $attributesQuery === '""' || $attributesQuery === '') {
                $userCart = $userCartQuery->where(function ($q) {
                    $q->whereNull('attributes')->orWhere('attributes', '[]')->orWhere('attributes', '""')->orWhere('attributes', '');
                })->first();
            } else {
                $userCart = $userCartQuery->where('attributes', $attributesQuery)->first();
            }

            if ($userCart) {
                $newQty = $userCart->quantity + $sessionCart->quantity;

                // Determine max stock qty ONLY if track_inventory is enabled
                if ($userCart->product && $userCart->product->track_inventory) {
                    $stock_qty = 0;
                    if ($userCart->attributes != null) {
                        $stockObj = ProductStock::showAvailableStock($userCart->product_id, json_encode($userCart->attributes));
                        $stock_qty = $stockObj ? $stockObj->quantity : 0;
                    } else {
                        if ($userCart->product->stocks) {
                            $stock_qty = $userCart->product->stocks->sum('quantity');
                        }
                    }

                    if ($stock_qty > 0 && $newQty > $stock_qty) {
                        $newQty = $stock_qty;
                    }
                }

                $userCart->quantity = $newQty;
                $userCart->save();

                $sessionCart->delete();
            } else {
                $sessionCart->user_id = $user_id;
                $sessionCart->session_id = null;
                $sessionCart->save();
            }
        }
    }
}
