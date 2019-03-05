<?php

use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Collection::class, 10)->create()->each(function($collection) {
            $collection->tracks()->saveMany(\App\Models\Track::inRandomOrder()->take(5)->get());
        });
    }
}
