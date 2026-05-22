<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasHidden
{
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where("is_hidden", false);
    }
}
