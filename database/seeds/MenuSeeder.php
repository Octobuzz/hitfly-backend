<?php

class MenuSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        // add default menus.
        \Encore\Admin\Auth\Database\Menu::insert([
            [
                'id' => 1,
                'parent_id' => 0,
                'order' => 1,
                'title' => 'Главная',
                'icon' => 'fa-bar-chart',
                'uri' => '/',
            ],
            [
                'id' => 2,
                'parent_id' => 0,
                'order' => 2,
                'title' => 'Админ',
                'icon' => 'fa-tasks',
                'uri' => '',
            ],
            [
                'id' => 3,
                'parent_id' => 2,
                'order' => 3,
                'title' => 'Пользователи',
                'icon' => 'fa-users',
                'uri' => 'auth/users',
            ],
            [
                'id' => 4,
                'parent_id' => 2,
                'order' => 4,
                'title' => 'Роли',
                'icon' => 'fa-user',
                'uri' => 'auth/roles',
            ],
            [
                'id' => 5,
                'parent_id' => 2,
                'order' => 5,
                'title' => 'Доступы',
                'icon' => 'fa-ban',
                'uri' => 'auth/permissions',
            ],
            [
                'id' => 6,
                'parent_id' => 2,
                'order' => 6,
                'title' => 'Меню',
                'icon' => 'fa-bars',
                'uri' => 'auth/menu',
            ],
            [
                'id' => 7,
                'parent_id' => 2,
                'order' => 7,
                'title' => 'Лог операций',
                'icon' => 'fa-history',
                'uri' => 'auth/logs',
            ],
            [
                'id' => 8,
                'parent_id' => 0,
                'order' => 8,
                'title' => 'Жанры',
                'icon' => 'fa-bar-chart',
                'uri' => '/genre',
            ],
            [
                'id' => 10,
                'parent_id' => 0,
                'order' => 10,
                'title' => 'Комментарии',
                'icon' => 'fa-bar-chart',
                'uri' => '/comment',
            ],
            [
                'id' => 11,
                'parent_id' => 0,
                'order' => 11,
                'title' => 'Альбомы',
                'icon' => 'fa-bar-chart',
                'uri' => '/album',
            ],
            [
                'id' => 12,
                'parent_id' => 0,
                'order' => 12,
                'title' => 'Музыкальные группы',
                'icon' => 'fa-bar-chart',
                'uri' => '/music/group',
            ],
            [
                'id' => 13,
                'parent_id' => 0,
                'order' => 13,
                'title' => 'Треки',
                'icon' => 'fa-bar-chart',
                'uri' => '/track',
            ],
        ]);
    }
}
