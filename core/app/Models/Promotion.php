<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\GlobalStatus;

class Promotion extends Model
{
    use GlobalStatus;
    protected $fillable = ['name', 'start_date', 'end_date', 'discount_type', 'discount_value', 'status'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'promotion_products', 'promotion_id', 'product_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'promotion_categories', 'promotion_id', 'category_id');
    }

    public function scopeActive($query)
    {
        $now = now();
        return $query->where('status', 1)
                     ->where('start_date', '<=', $now)
                     ->where('end_date', '>=', $now);
    }
}
