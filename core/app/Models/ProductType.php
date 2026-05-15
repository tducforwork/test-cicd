<?php

namespace App\Models;

use App\Models\Key;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    protected $guarded = ['id'];

    protected static function booted()
    {
        static::saved(function ($type) {
            Key::updateOrCreate(
                ['type' => Key::TYPE_PRODUCT_TYPE, 'key_id' => $type->id],
                ['slug' => $type->slug]
            );
        });

        static::deleted(function ($type) {
            Key::where('type', Key::TYPE_PRODUCT_TYPE)->where('key_id', $type->id)->delete();
        });
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_product_types');
    }
}
