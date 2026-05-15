<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $guarded = ['id'];
    protected $appends = ['full_name'];

    public function wards()
    {
        return $this->hasMany(Ward::class);
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
