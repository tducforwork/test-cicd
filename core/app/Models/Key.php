<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Key extends Model
{
    protected $fillable = ['slug', 'type', 'key_id'];

    const TYPE_CATEGORY = 'category';
    const TYPE_PRODUCT = 'product';
    const TYPE_NEWS = 'news';
    const TYPE_NEWS_CATEGORY = 'news_category';
    const TYPE_BRAND = 'brand';
    const TYPE_PRODUCT_TYPE = 'product_type';
}
