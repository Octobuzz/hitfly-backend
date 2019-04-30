<?php

namespace App\Http\GraphQL\Enums;

use Rebing\GraphQL\Support\Type as GraphQLType;

class MusicGroupSizeEnum extends GraphQLType
{
    protected $enumObject = true;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $sizes = array_keys(config('image.size.music_group'));
        $key = array_search('default', $sizes);
        if (false !== $key) {
            unset($sizes[$key]);
        }

        $this->attributes = [
            'name' => 'MusicGroupSizeEnum',
            'description' => 'Размеры аватарок музыкальных групп',
            'values' => $sizes,
        ];
    }
}
