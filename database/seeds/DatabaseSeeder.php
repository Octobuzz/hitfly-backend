<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        DB::table('watcheables')->delete();
        DB::table('likes')->delete();
        DB::table('collections')->delete();
        DB::table('comments')->delete();
        DB::table('tracks')->delete();
        DB::table('albums')->delete();
        DB::table('music_group')->delete();
        DB::table('genres')->delete();
        DB::table('users')->delete();
        DB::table('cities')->delete();
        DB::table('admin_role_menu')->delete();
        DB::table('admin_menu')->delete();
        DB::table('admin_operation_log')->delete();
        DB::table('admin_permissions')->delete();
        DB::table('admin_role_permissions')->delete();
        DB::table('admin_role_users')->delete();
        DB::table('admin_roles')->delete();
        DB::table('admin_config')->delete();
        DB::table('orders')->delete();
        DB::table('products')->delete();
        DB::table('attributes')->delete();
        DB::table('product_attribute')->delete();

        if (App::environment('local')) {
            $this->call([
                PermissionSeeder::class,
                RoleSeeder::class,
                MenuSeeder::class,
                CitySeeder::class,
//                GenreSeeder::class,
                UserSeeder::class,
//                MusicGroupSeeder::class,
//                AlbumsSeeder::class,
//                TracksSeeder::class,
//                CommentsSeeder::class,
//                CollectionSeeder::class,
//                LikesSeeder::class,
//                WatchingSeeder::class,
//                FavouritesSeeder::class,
                ConfigSeeder::class,
                ProductsSeeder::class,
            ]);
        }
        if (App::environment('prod')) {
            $this->call([
                PermissionSeeder::class,
                RoleSeeder::class,
                MenuSeeder::class,
                UserSeeder::class,
                ConfigSeeder::class,
                ProductsSeeder::class,
            ]);
        }
    }
}
