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
    }
}
