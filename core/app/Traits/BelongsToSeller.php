<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait BelongsToSeller
{
    public function scopeBelongsToSeller($query, $relation = null)
    {
        if ($relation) {
            return $query->whereHas($relation, function ($q2) {
                $q2->where('seller_id', Auth::id());
            });
        }

        return $query->where('seller_id', Auth::id());
    }
}
