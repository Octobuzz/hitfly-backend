<?php
/**
 * Created by PhpStorm.
 * User: Dmitrii
 * Date: 17.04.2019
 * Time: 15:51.
 */

namespace App\Http\GraphQL\Fields;

use App\Models\Album;
use App\Models\Collection;
use App\Models\MusicGroup;
use App\Models\Track;
use App\Rules\FactorRule;
use GraphQL\Type\Definition\Type;

class PictureField extends BasicPictureField
{
    /**
     * @var Album | Collection | Track | MusicGroup
     */
    protected $attributes = [
        'description' => 'Получение изображений',
        'selectable' => false,
    ];

    public function type()
    {
        return Type::listOf(\GraphQL::type('ImageSizesType'));
    }

    public function args()
    {
        return [
            'sizes' => [
                'type' => Type::nonNull(Type::listOf(\GraphQL::type('PictureSizeEnum'))),
                'description' => 'Размеры изображений',
            ],
            'factor' => [
                'type' => Type::float(),
                'description' => 'Множитель',
                'rules' => [new FactorRule()],
            ],
        ];
    }
}
