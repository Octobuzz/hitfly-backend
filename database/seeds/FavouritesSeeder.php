<?php

use Illuminate\Database\Seeder;

class FavouritesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        factory(\App\Models\Favourite::class, 300)->create();
    }
}
