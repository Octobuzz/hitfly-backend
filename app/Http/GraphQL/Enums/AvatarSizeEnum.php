<?php

namespace App\Http\GraphQL\Enums;

use Rebing\GraphQL\Support\Type as GraphQLType;

class AvatarSizeEnum extends GraphQLType
{
    protected $enumObject = true;
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $sizes = array_keys (config('image.size.avatar'));
        $key = array_search ('default', $sizes);
        if($key!==false)
        {
            unset($sizes[$key]);
        }

        $this->attributes = [
            'name' => 'AvatarSizeEnum',
            'description' => 'Размеры аватарок',
            'values' =>
                $sizes

        ];
    }

}
