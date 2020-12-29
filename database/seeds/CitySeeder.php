<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 01.04.19
 * Time: 15:17.
 */
class CitySeeder extends \Illuminate\Database\Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        factory(\App\Models\City::class, 50)->create();
    }
}
