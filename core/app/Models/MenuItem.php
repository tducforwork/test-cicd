<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use GlobalStatus;
    protected $guarded = ['id'];

    public function group()
    {
        return $this->belongsTo(MenuGroup::class, 'menu_group_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id')->orderBy('order');
    }
}
