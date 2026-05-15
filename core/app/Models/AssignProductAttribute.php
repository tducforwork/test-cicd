<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignProductAttribute extends Model
{

    protected $guarded  = ['id'];


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productAttribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'product_attribute_id');
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'assign_product_attribute_id');
    }


    public static function productAttributes($pid, $aid)
    {
        $data = self::where('status', 1)->where('product_id', $pid)->where('product_attribute_id', $aid)->with('product', 'productAttribute')->get();

        return $data;
    }

    public static function priceAfterAttribute($product, $attributes)
    {
        $price = $product->final_price;
        $varient_Price = 0;
        if (!empty($attributes)) {
            if (is_string($attributes)) {
                $attributes = json_decode($attributes, true);
            }
            $varient_Price = self::whereIn('id', (array)$attributes)->sum('extra_price');
        }
        return $price + $varient_Price;
    }

    public static function cartAttributesShow($attributes){
        $varients = '';

        $attr_data  =  self::with('productAttribute')->get();

        foreach ($attributes as $aid) {
            $varients .=  $attr_data->where('id', $aid)->first()->productAttribute->name_for_user . ' : ';
            $varients .= $attr_data->where('id', $aid)->first()->name . '<br>';
        }
        return $varients;
    }

    public static function productAttributesDetails($attributes)
    {
        $variants   = '';
        $attr_data  =  self::with('productAttribute')->get();
        $variants   = [];
        $extra_price = 0;
        foreach ($attributes as $key=>$aid) {
            $price = $attr_data->where('id', $aid)->first()->extra_price;
            $variants[$key]['name']   =  $attr_data->where('id', $aid)->first()->productAttribute->name_for_user;
            $variants[$key]['value']  = $attr_data->where('id', $aid)->first()->name;
            $variants[$key]['price']  = $price;

            $extra_price += $price;
        }
        $details['variants'] = $variants;
        $details['extra_price'] = $extra_price;

        return $details;
    }

}
