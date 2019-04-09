<?php

class MenuSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        // add default menus.
        \Encore\Admin\Auth\Database\Menu::insert([
            [
                'parent_id' => 0,
                'order' => 1,
                'title' => 'Главная',
                'icon' => 'fa-bar-chart',
                'uri' => '/',
            ],
            [
                'parent_id' => 0,
                'order' => 2,
                'title' => 'Админ',
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
                'title' => 'Доступы',
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
                'title' => 'Лог операций',
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
                'order' => 11,
                'title' => 'Альбомы',
                'icon' => 'fa-bar-chart',
                'uri' => '/album',
            ],
            [
                'parent_id' => 0,
                'order' => 11,
                'title' => 'Музыкальные группы',
                'icon' => 'fa-bar-chart',
                'uri' => '/music/group',
            ],
            [
                'parent_id' => 0,
                'order' => 12,
                'title' => 'Треки',
                'icon' => 'fa-bar-chart',
                'uri' => '/track',
            ],
        ]);
    }
}
