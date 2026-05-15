<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    protected $guarded = ['id'];
    protected $appends = ['full_name'];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function getFullNameAttribute()
    {
        return $this->name;
    }
}
