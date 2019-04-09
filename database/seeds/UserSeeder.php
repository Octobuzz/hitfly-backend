<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // create a role.
        \Encore\Admin\Auth\Database\Role::truncate();
        \Encore\Admin\Auth\Database\Role::insert([
            [
            'name' => 'Administrator',
            'slug' => 'administrator',
            ],
            [
                'name' => 'Слушатель',
                'slug' => 'listener',
            ],
            [
                'name' => 'Исполнитель',
                'slug' => 'performer',
            ],
            [
                'name' => 'Критик',
                'slug' => 'critic',
            ],
        ]);

        // create a user.
        \App\User::truncate();
        \App\User::create([
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'email' => 'example@example.com',
            'gender' => 'M',
        ]);

        // add role to user.
        \App\User::first()->roles()->save(\Encore\Admin\Auth\Database\Role::first());

        //create a permission
        \Encore\Admin\Auth\Database\Permission::truncate();
        \Encore\Admin\Auth\Database\Permission::insert([
            [
                'name' => 'All permission',
                'slug' => '*',
                'http_method' => '',
                'http_path' => '*',
            ],
            [
                'name' => 'Dashboard',
                'slug' => 'dashboard',
                'http_method' => 'GET',
                'http_path' => '/',
            ],
            [
                'name' => 'Login',
                'slug' => 'auth.login',
                'http_method' => '',
                'http_path' => "/auth/login\r\n/auth/logout",
            ],
            [
                'name' => 'User setting',
                'slug' => 'auth.setting',
                'http_method' => 'GET,PUT',
                'http_path' => '/auth/setting',
            ],
            [
                'name' => 'Auth management',
                'slug' => 'auth.management',
                'http_method' => '',
                'http_path' => "/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs",
            ],
            [
                'name' => 'Комментарии критиков',
                'slug' => 'comment.сricic',
                'http_method' => '',
                'http_path' => '',
            ],
        ]);

        \Encore\Admin\Auth\Database\Role::first()->permissions()->save(\Encore\Admin\Auth\Database\Permission::first());
        \Encore\Admin\Auth\Database\Role::find(4)->permissions()->save(\Encore\Admin\Auth\Database\Permission::find(6));

        // add default menus.
        \Encore\Admin\Auth\Database\Menu::truncate();
        \Encore\Admin\Auth\Database\Menu::insert([
            [
                'parent_id' => 0,
                'order' => 1,
                'title' => 'Index',
                'icon' => 'fa-bar-chart',
                'uri' => '/',
            ],
            [
                'parent_id' => 0,
                'order' => 2,
                'title' => 'Администратор',
                'icon' => 'fa-tasks',
                'uri' => '',
            ],
            [
                'parent_id' => 2,
                'order' => 3,
                'title' => 'Пользователи',
                'icon' => 'fa-users',
                'uri' => 'auth/users',
            ],
            [
                'parent_id' => 2,
                'order' => 4,
                'title' => 'Роли',
                'icon' => 'fa-user',
                'uri' => 'auth/roles',
            ],
            [
                'parent_id' => 2,
                'order' => 5,
                'title' => 'Права доступа',
                'icon' => 'fa-ban',
                'uri' => 'auth/permissions',
            ],
            [
                'parent_id' => 2,
                'order' => 6,
                'title' => 'Меню',
                'icon' => 'fa-bars',
                'uri' => 'auth/menu',
            ],
            [
                'parent_id' => 2,
                'order' => 7,
                'title' => 'Журнал операций',
                'icon' => 'fa-history',
                'uri' => 'auth/logs',
            ],
            [
                'parent_id' => 0,
                'order' => 8,
                'title' => 'Жанры',
                'icon' => 'fa-bar-chart',
                'uri' => '/genre',
            ],
            [
                'parent_id' => 0,
                'order' => 9,
                'title' => 'Группы',
                'icon' => 'fa-bar-chart',
                'uri' => '/group',
            ],
            [
                'parent_id' => 0,
                'order' => 10,
                'title' => 'Комментарии',
                'icon' => 'fa-bar-chart',
                'uri' => '/comment',
            ],
            [
                'parent_id' => 0,
                'order' => 10,
                'title' => 'Чарты',
                'icon' => 'fa-bar-chart',
                'uri' => '/chart',
            ],
        ]);

        // add role to menu.
        \Encore\Admin\Auth\Database\Menu::find(2)->roles()->save(\Encore\Admin\Auth\Database\Role::first());
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        factory(\App\User::class, 30)->create()->each(function ($u) {
            $u->roles()->save(\Encore\Admin\Auth\Database\Role::where('id', '>', 1)->inRandomOrder()->first());
        });
    }
}
