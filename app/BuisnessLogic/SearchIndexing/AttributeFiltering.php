<?php

namespace App\BuisnessLogic\SearchIndexing;

use App\Models\Album;
use App\Models\Collection;
use App\Models\Track;
use App\User;

class AttributeFiltering
{
    public function filterTrack(Track $collection): array
    {
        return collect($collection)->only(['track_name', 'singer', 'song_text'])->all();
    }

    public function filterAlbum(Album $collection): array
    {
        return collect($collection)->only(['title'])->all();
    }
    public function filterUser(User $collection): array
    {
        return collect($collection)->only(['username'])->all();
    }
    public function filterCollection(Collection $collection): array
    {
        return collect($collection)->only(['title'])->all();
    }
}
