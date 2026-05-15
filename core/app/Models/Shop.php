<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'meta_keywords' => 'array',
        'social_links' => 'array'
    ];
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
