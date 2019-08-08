<?php

use Illuminate\Database\Seeder;
use App\Models\BonusType;

class BonusTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        BonusType::query()->create([
            'name' => 'Загрузка аватара',
            'constant_name' => 'AVATAR',
            'bonus' => '50',
            'description' => 'Добавление аватара',
            'show_user' => 1,
        ]);
        BonusType::query()->create([
            'name' => 'Имя пользователя',
            'constant_name' => 'USER_NAME',
            'bonus' => '50',
            'description' => 'Заполнение имени пользователя',
            'show_user' => 1,
        ]);
        BonusType::query()->create([
            'name' => 'Город',
            'constant_name' => 'CITY',
            'bonus' => '50',
            'description' => 'Заполнение города',
            'show_user' => 1,
        ]);
        BonusType::query()->create([
            'name' => 'Год начала карьеры',
            'constant_name' => 'CAREER_START',
            'bonus' => '50',
            'description' => 'Заполнение года начала карьеры музыканта',
            'show_user' => 1,
        ]);
        BonusType::query()->create([
            'name' => 'Жанры исполнителя',
            'constant_name' => 'ARTIST_GENRES',
            'bonus' => '50',
            'description' => 'Заполнение жанров в которых играет исполнитель',
            'show_user' => 1,
        ]);
        BonusType::query()->create([
            'name' => 'Описание деятельности',
            'constant_name' => 'ARTIST_DESCRIPTION',
            'bonus' => '50',
            'description' => 'Заполнение описания деятельности исполнителя',
            'show_user' => 1,
        ]);
        BonusType::query()->create([
            'name' => 'Соцсети',
            'constant_name' => 'SOCIALS',
            'bonus' => '50',
            'description' => 'Заполнение соцсетей',
            'show_user' => 1,
        ]);
    }
}
