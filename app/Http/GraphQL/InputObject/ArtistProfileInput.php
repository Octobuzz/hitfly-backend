<?php

namespace App\Http\GraphQL\InputObject;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ArtistProfileInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'ArtistProfileInput',
        'description' => 'Дополнительные поля артиста',
    ];

    public function fields()
    {
        return [
            'description' => [
                'name' => 'description',
                'description' => 'Имя пользователя',
                'type' => Type::string(),
            ],
            'careerStart' => [
                'name' => 'careerStart',
                'description' => 'Начало карьеры',
                'type' => Type::string(),
            ],
            'genres' => [
                'name' => 'genres',
                'description' => 'Жанры в которых играет артист',
                'type' => Type::listOf(Type::id()),
            ],
        ];
    }
}
