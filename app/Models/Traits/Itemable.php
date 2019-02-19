<?php

namespace App\Models\Traits;


use App\Models\Favourite;

trait Itemable
{
    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favouriteable');
    }
}