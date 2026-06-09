<?php

namespace App\Models\Traits;

trait HasSortOrder
{
    public static function bootHasSortOrder()
    {
        static::addGlobalScope('sort_order', fn ($q) => $q->orderBy('sort_order')->orderBy('id'));
    }
}
