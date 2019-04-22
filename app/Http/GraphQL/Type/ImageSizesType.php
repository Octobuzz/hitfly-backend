<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 01.04.19
 * Time: 14:46.
 */

namespace App\Http\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ImageSizesType extends GraphQLType
{
    protected $attributes = [
        'name' => 'ImageSizesType',
        'description' => 'Размеры аватаров',
    ];

    public function fields()
    {
        return [
            'name' => [
                'type' => Type::string(),
                'description' => 'название размера',
            ],
            'url' => [
                'type' => Type::string(),
                'description' => 'url изображения',
            ],
        ];
    }
}
