<?php
use Illuminate\Database\Seeder;

class TracksSeeder  extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Track::class, 300)->create();
    }
}
