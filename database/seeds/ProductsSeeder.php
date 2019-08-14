<?php

use Illuminate\Database\Seeder;
use App\Models\Attribute;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductsSeeder extends Seeder
{
    const PRODUCTS_DIR = 'products/';

    /**
     *  создание товаров.
     */
    public function run()
    {
        //$this->createCommentFromStar();
        $this->createStudiosProducts();
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

    /*
     * группа товаров - услуги в студии
     */
    private function createStudiosProducts()
    {
        $productsArr = [
            [
                'name' => 'Услуги в студии',
                'description' => 'Запись вокала',
                'alias' => 'STUDIO_VOCAL',
                'price' => 10000,
            ],
            [
                'name' => 'Услуги в студии',
                'description' => 'Тональная коррекция (тюнинг вокала)',
                'alias' => 'STUDIO_TONE_CORRECTION',
                'price' => 10500,
            ],
            [
                'name' => 'Услуги в студии',
                'description' => 'Запись музыкальных инструментов',
                'alias' => 'STUDIO_INSTRUMENTAL',
                'price' => 11500,
            ],
            [
                'name' => 'Услуги в студии',
                'description' => 'Мастеринг трека',
                'alias' => 'STUDIO_MASTERING',
                'price' => 12000,
            ],
            [
                'name' => 'Услуги в студии',
                'description' => 'Стэм-мастеринг трека',
                'alias' => 'STUDIO_STEM_MASTERING',
                'price' => 12500,
            ],
            [
                'name' => 'Услуги в студии',
                'description' => 'Сведение вокала с готовым минусом',
                'alias' => 'STUDIO_VOCAL_MINUS',
                'price' => 13000,
            ],
            [
                'name' => 'Услуги в студии',
                'description' => 'Сведение + Мастеринг трека',
                'alias' => 'STUDIO_MIXING_AND_MASTERING',
                'price' => 20000,
            ],
        ];
        $publicPath = Storage::disk('public')->getAdapter()->getPathPrefix();
        if (!file_exists($publicPath.self::PRODUCTS_DIR)) {
            Storage::disk('public')->makeDirectory(self::PRODUCTS_DIR);
        }

        foreach ($productsArr as $value) {
            $product = new Product($value);
            $product->save();
            $file = Storage::disk('local')->get('products/STUDIO_INSTRUMENTAL.png');
            $imgNamePublic = self::PRODUCTS_DIR.$product->id.'/STUDIO_INSTRUMENTAL.png';

            if (!file_exists($publicPath.self::PRODUCTS_DIR.$product->id)) {
                Storage::disk('public')->makeDirectory(self::PRODUCTS_DIR.$product->id);
            }
            Storage::disk('public')->put($imgNamePublic, $file);
            $product->image = $imgNamePublic;
            $product->save();
        }
    }
}
