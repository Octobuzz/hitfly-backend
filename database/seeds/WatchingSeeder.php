<?php

class WatchingSeeder extends \Illuminate\Database\Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        factory(\App\Models\Watcheables::class, 100)->create();
    }
}
