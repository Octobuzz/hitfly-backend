<?php

namespace App\Repositories;

use App\Models\Album;

class AlbumRepository extends SearchRepository
{
    protected $model = Album::class;
}
