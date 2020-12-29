<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 09.04.19
 * Time: 16:16.
 */
class RoleSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
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
            [
                'name' => 'Профессиональный критик',
                'slug' => 'prof_critic',
            ],
            [
                'name' => 'Звезда',
                'slug' => 'star',
            ],
        ]);
    }
}
