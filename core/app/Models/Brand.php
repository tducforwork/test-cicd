<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Key;


class Brand extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    protected static function booted()
    {
        static::saved(function ($brand) {
            Key::updateOrCreate(
                ['type' => Key::TYPE_BRAND, 'key_id' => $brand->id],
                ['slug' => $brand->slug]
            );
        });

        static::deleted(function ($brand) {
            Key::where('type', Key::TYPE_BRAND)->where('key_id', $brand->id)->delete();
        });
    }

    protected $casts = [
        'meta_keywords' => 'array'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }


    public function scopeTop($query)
    {
        return $query->where('top', 1);
    }

    public function logo()
    {
        return getImage(getFilePath('brand') . '/' . $this->logo, getFileSize('brand'));
    }
}
