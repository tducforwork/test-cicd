<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\BelongsToSeller;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use SoftDeletes, BelongsToSeller, GlobalStatus;

    protected $guarded = ['id'];

    protected $casts = [
        'specification' => 'array',
        'meta_keywords' => 'array'
    ];

    /**
     * Prepare a date for array / JSON serialization.
     * Ensure JSON is not escaped for Unicode characters.
     */
    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'products_categories', 'product_id', 'category_id');
    }

    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'offers_products', 'product_id', 'offer_id');
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupons_products', 'product_id', 'coupon_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function productImage()
    {
        return getImage(getFilePath('product') . '/' . $this->main_image, getFileSize('product'));
    }

    public function offer()
    {
        return $this->hasOne(OffersProduct::class, 'product_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function brands()
    {
        return $this->brand();
    }

    public function productType()
    {
        return $this->belongsToMany(ProductType::class, 'product_product_types');
    }

    public function productTypes()
    {
        return $this->productType();
    }
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function scopeSellers($query)
    {
        return $query->where('seller_id', Auth::id());
    }

    public function assignAttributes()
    {
        return $this->hasMany(AssignProductAttribute::class, 'product_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'product_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id');
    }

    public function userReview()
    {
        return $this->hasOne(ProductReview::class, 'product_id')->where('user_id', Auth::id());
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function productPreviewImages()
    {
        return $this->hasMany(ProductImage::class)->where('assign_product_attribute_id', 0);
    }

    public function productVariantImages()
    {
        return $this->hasMany(ProductImage::class)->where('assign_product_attribute_id', '!=', 0);
    }

    public function promotions()
    {
        return $this->belongsToMany(Promotion::class, 'promotion_products', 'product_id', 'promotion_id');
    }

    public function getActivePromotionAttribute()
    {
        return $this->promotions()->active()->first();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags', 'product_id', 'tag_id');
    }

    public function getFinalPriceAttribute()
    {
        $basePrice = $this->base_price;
        
        // Prioritize product's own discount price if set
        if ($this->discount_price > 0) {
            return $this->discount_price;
        }

        $activePromotion = $this->active_promotion;
        if ($activePromotion) {
            $promoDiscount = 0;
            if ($activePromotion->discount_type == 1) { // Fixed
                $promoDiscount = $activePromotion->discount_value;
            } else { // Percentage
                $promoDiscount = ($basePrice * $activePromotion->discount_value) / 100;
            }

            $promoPrice = max(0, $basePrice - $promoDiscount);
            return $promoPrice;
        }

        return $basePrice;
    }

    public function scopeActive($query)
    {
        return $query->where('status', Status::ENABLE);
    }
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', Status::YES);
    }

    public function scopePending($query)
    {
        return $query->where('status', Status::DISABLE);
    }

    public function scopePublishable($query)
    {
        return $query->active()->where(function ($q2) {
            $q2->where('has_variants', Status::NO)->orWhereHas('assignAttributes');
        })->where(function ($q) {
            $q->where('seller_id', 0)->orWhereHas('seller', function ($seller) {
                $seller->active();
            });
        });
    }


    public static function topSales($limit = 6)
    {
        return self::leftJoin('order_details', 'products.id', '=', 'order_details.product_id')
            ->leftJoin('sub_orders', 'order_details.sub_order_id', '=', 'sub_orders.id')
            ->leftJoin('orders', 'sub_orders.order_id', '=', 'orders.id')
            ->selectRaw('products.*, COALESCE(sum(order_details.quantity),0) total')
            ->where('orders.payment_status', '!=', '0')
            ->groupBy('products.id')
            ->with('reviews')
            ->orderBy('total', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getTotalQuantityAttribute()
    {
        return $this->stocks->sum('quantity');
    }

}
