<?php

use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \Encore\Admin\Config\Config::insert([
            [
                'name' => 'comment_edit',
                'value' => '5',
                'description' => 'В течение скольких часов,  можно редатктировать свой отзыв на альбом/трек',
            ],
        ]);
    }
}
