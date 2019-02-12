<?php

use Illuminate\Database\Seeder;


class MusicGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\MusicGroup::class, 30)->create();
    }
}
