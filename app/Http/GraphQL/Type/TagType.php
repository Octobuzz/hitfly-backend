<?php

namespace App\Http\GraphQL\Type;

use App\Models\Tag;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class TagType extends GraphQLType
{
    protected $attributes = [
        'name' => 'TagType',
        'model' => Tag::class,
        'description' => 'Тэги',
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::int(),
                'description' => 'ID тэга',
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'Заголовок',
            ],
        ];
    }
}
