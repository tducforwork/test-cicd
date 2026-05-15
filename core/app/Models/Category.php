<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Category extends Model
{
    protected $guarded  = ['id'];
    protected $casts = [
        'meta_keywords' => 'array'
    ];

    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id')->with('parent');
    }

    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_id')->withCount(['products' => function($q) { $q->publishable(); }])->orderBy('name');
    }

    public function allSubcategories()
    {
        return $this->subcategories()->with('allSubcategories')->withCount(['products' => function($q) { $q->publishable(); }]);
    }

    public function getAllChildIds()
    {
        $ids = [$this->id];
        foreach ($this->allSubcategories as $sub) {
            $ids = array_merge($ids, $sub->getAllChildIds());
        }
        return $ids;
    }

    public function getTotalProductsCountAttribute()
    {
        $allIds = $this->getAllChildIds();
        return Product::publishable()->whereHas('categories', function ($q) use ($allIds) {
            $q->whereIn('categories.id', $allIds);
        })->count();
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupons_categories', 'category_id', 'coupon_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_categories', 'category_id', 'product_id');
    }

    public function categoryImage()
    {
        return getImage(getFilePath('category') . '/' . $this->image, getFileSize('category'));
    }


    public function specialProuducts()
    {
        return $this->belongsToMany(Product::class, 'products_categories', 'category_id', 'product_id')->with('offer', 'offer.activeOffer', 'reviews')->orderBy('id', 'desc')->limit(15);
    }

    public function scopeIsParent($query)
    {
        return $query->whereNull('parent_id');
    }
}
