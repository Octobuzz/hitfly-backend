<?php

namespace App\Http\GraphQL\Enums;

use Rebing\GraphQL\Support\Type as GraphQLType;

class AvatarSizeEnum extends GraphQLType
{
    protected $enumObject = true;
    /*public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $this->attributes = [
            'name' => 'AvatarSizeEnum',
            'description' => 'Размеры аватарок',
            'values' => [
            'size_235x235' => [
                config('image.size.avatar.default.width') ? config('image.size.avatar.default.width'):235,
                config('image.size.avatar.default.height') ? config('image.size.avatar.default.height'):235
            ],
            'size_56x56' => [
                config('image.size.avatar.56x56.width') ? config('image.size.avatar.56x56.width'):56,
                config('image.size.avatar.56x56.height') ? config('image.size.avatar.56x56.height'):56,
                ],
            ]
        ];

    }*/

    protected $attributes = [
        'name' => 'AvatarSizeEnum',
        'description' => 'Размеры аватарок',
        'values' => [
            'size_235x235',
            'size_56x56'
        ],
    ];
}
