<?php

namespace App\Http\GraphQL\InputObject;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class AlbumInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'AlbumInput',
        'description' => 'Альбом',
    ];

    public function fields()
    {
        return [
            'type' => [
                'name' => 'type',
                'description' => 'Тип альбома',
                'type' => \GraphQL::type('AlbumTypeEnum'),
                'rules' => ['required'],
            ],
            'title' => [
                'name' => 'title',
                'description' => 'Название',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['max:250', 'required'],
            ],
            'author' => [
                'name' => 'author',
                'description' => 'Автор max:250 символов',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['max:250', 'required'],
            ],
            'year' => [
                'name' => 'year',
                'description' => 'год',
                'type' => Type::nonNull(Type::int()),
                'rules' => ['required', 'date_format:Y'],
            ],
            'genres' => [
                'name' => 'genres',
                'description' => 'id жанров',
                'type' => Type::listOf(Type::int()),
                'rules' => ['required'],
            ],
        ];
    }
}
