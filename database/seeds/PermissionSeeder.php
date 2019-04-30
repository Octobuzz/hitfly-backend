<?php

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        //create a permission
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
            [
                'name' => 'Комментарии звезд',
                'slug' => 'comment.star',
                'http_method' => '',
                'http_path' => '',
            ],
        ]);
    }
}
