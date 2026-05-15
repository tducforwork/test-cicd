<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReviewImage extends Model
{
    protected $guarded = ['id'];

    public function productReview()
    {
        return $this->belongsTo(ProductReview::class, 'product_review_id');
    }
}
