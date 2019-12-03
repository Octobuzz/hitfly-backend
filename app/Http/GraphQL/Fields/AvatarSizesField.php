<?php
/**
 * Created by PhpStorm.
 * User: Dmitrii
 * Date: 17.04.2019
 * Time: 15:51.
 */

namespace App\Http\GraphQL\Fields;

use App\Rules\FactorRule;
use GraphQL\Type\Definition\Type;

class AvatarSizesField extends BasicPictureField
{
    protected $attributes = [
        'description' => 'Аватар',
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
                'type' => Type::nonNull(Type::listOf(\GraphQL::type('AvatarSizeEnum'))),
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
