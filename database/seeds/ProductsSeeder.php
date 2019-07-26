<?php

use Illuminate\Database\Seeder;
use App\Models\Attribute;
use App\Models\Product;

class ProductsSeeder extends Seeder
{
    /**
     *  создание товаров.
     */
    public function run()
    {
        $this->createCommentFromStar();
    }

    /*
     * товар - коммент от звезды
     */
    private function createCommentFromStar()
    {
        $trackAttribute = new Attribute([
            'name' => 'id трека',
            'model' => \App\Models\Track::class,
            'alias' => 'TRACK_ID',
        ]);
        $trackAttribute->save();
        $starAttribute = new Attribute([
            'name' => 'id звезды',
            'model' => \App\User::class,
            'alias' => 'STAR_ID',
        ]);
//       \Illuminate\Support\Facades\Log::debug(json_encode($trackAttribute));die();
        $starAttribute->save();
        $commentFromStarProduct = new Product([
            'name' => 'Отзыв от звезды',
            'description' => 'Покупка отзыва от звезды',
            'alias' => 'COMMENT_FROM_STAR',
            'price' => 2000,
        ]);
        $commentFromStarProduct->save();
        $commentFromStarProduct->attributes()->attach([$trackAttribute->id, $starAttribute->id]);
    }
}
