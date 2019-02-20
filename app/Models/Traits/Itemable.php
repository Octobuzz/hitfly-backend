<?php

namespace App\Models\Traits;

use App\Models\Favourite;
use App\Models\Like;

trait Itemable
{
    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favouriteable');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
