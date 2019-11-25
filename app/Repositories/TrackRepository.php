<?php

namespace App\Repositories;

use App\Models\Track;

class TrackRepository extends SearchRepository
{
    protected $model = Track::class;
}
