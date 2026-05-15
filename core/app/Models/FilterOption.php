<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilterOption extends Model
{
    protected $guarded = ['id'];

    public function group()
    {
        return $this->belongsTo(FilterGroup::class, 'filter_group_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_filter_values', 'filter_option_id', 'product_id');
    }
}
