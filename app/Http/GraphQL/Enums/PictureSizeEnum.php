<?php

namespace App\Http\GraphQL\Enums;

use Rebing\GraphQL\Support\Type as GraphQLType;

class PictureSizeEnum extends GraphQLType
{
    protected $enumObject = true;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $sizes = array_keys(config('image.size.allSizes'));

        $this->attributes = [
            'name' => 'PictureSizeEnum',
            'description' => 'Размеры картинок',
            'values' => $sizes,
        ];
    }
}
