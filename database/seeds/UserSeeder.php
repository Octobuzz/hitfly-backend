<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \App\User::create([
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'email' => 'example@example.com',
            'gender' => 'M',
        ]);

        // add role to user.
        \App\User::first()->roles()->save(\Encore\Admin\Auth\Database\Role::first());

        \Encore\Admin\Auth\Database\Role::first()->permissions()->save(\Encore\Admin\Auth\Database\Permission::first());
        \Encore\Admin\Auth\Database\Role::query()->where('slug', '=', 'critic')->first()
            ->permissions()
            ->save(\Encore\Admin\Auth\Database\Permission::query()->where('slug', '=', 'comment.сricic')->first());

        \Encore\Admin\Auth\Database\Role::query()->where('slug', '=', 'star')->first()
            ->permissions()
            ->save(\Encore\Admin\Auth\Database\Permission::query()->where('slug', '=', 'comment.star')->first());

        // add role to menu.
        \Encore\Admin\Auth\Database\Menu::query()->where('order', '=', 2)->first()
            ->roles()->save(\Encore\Admin\Auth\Database\Role::first());

        if (App::environment('local')) {
            factory(\App\User::class, 30)->create()->each(function ($u) {
            });
        }
    }
}
