<?php

use Illuminate\Database\Seeder;

class MusicGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        factory(\App\Models\MusicGroup::class, 30)->create();

        //привязка жанров к альбомам (многие ко многим)
        $genres = \App\Models\Genre::all();
        \App\Models\MusicGroup::all()->each(function ($group) use ($genres) {
            $group->genres()->sync(
            //$genres->random(rand(1, 3))->pluck('id')->toArray()
                $genres->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
