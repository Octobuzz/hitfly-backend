<?php

namespace App\BuisnessLogic\SearchIndexing;

use App\Models\Album;
use App\Models\Track;

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
}
