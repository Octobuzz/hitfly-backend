<?php

namespace Tests;

use App\Models\Album;
use App\Models\City;
use App\Models\Genre;
use App\Models\MusicGroup;
use App\Models\Role;
use App\Models\Track;
use App\User;

class PrepareData
{
    /**
     * @param int $count
     *
     * @return mixed
     */
    public static function createUsers($count = 1)
    {
        factory(City::class)->create();
        factory(Role::class, 4)->create();
        if (1 === $count) {
            return factory(User::class)->create();
        }

        return factory(User::class, $count)->create();
    }

    /**
     * @param int $count
     *
     * @return mixed
     */
    public static function createTracks($count = 3)
    {
        factory(MusicGroup::class)->create();
        factory(Album::class)->create();
        factory(Genre::class)->create();
        if (1 === $count) {
            return factory(Track::class)->create();
        }

        return factory(Track::class, $count)->create();
    }
}
