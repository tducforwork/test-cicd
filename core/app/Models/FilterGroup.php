<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilterGroup extends Model
{
    protected $guarded = ['id'];

    public function options()
    {
        return $this->hasMany(FilterOption::class, 'filter_group_id')->orderBy('value');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
