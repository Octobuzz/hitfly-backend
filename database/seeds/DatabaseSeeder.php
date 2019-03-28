<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            GenreSeeder::class,
            MusicGroupSeeder::class,
            AlbumsSeeder::class,
            TracksSeeder::class,
            CommentsSeeder::class,
//            CollectionSeeder::class,
        ]);
    }
}
