<?php

class WatchingSeeder extends \Illuminate\Database\Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        factory(\App\Models\Watching::class, 100)->create();
    }
}
