<?php

namespace App\Http\GraphQL\InputObject;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class TrackInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'TrackInput',
        'description' => 'Информация о треке',
    ];

    public function fields()
    {
        return [
            'trackName' => [
                'name' => 'trackName',
                'description' => 'Название трека',
                'type' => Type::string(),
                'rules' => ['required', 'max:250'],
            ],
            'album' => [
                'name' => 'album',
                'description' => 'Альбом трека',
                'type' => Type::int(),
                'rules' => ['nullable', 'integer'],
            ],
            'genres' => [
                'name' => 'genres',
                'description' => 'Жанры трека',
                'type' => Type::listOf(Type::int()),
                'rules' => ['required', 'array'],
            ],
            'singer' => [
                'name' => 'singer',
                'description' => 'Испольнитель',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required', 'min:0', 'max:250'],
            ],
            'trackDate' => [
                'name' => 'trackDate',
                'description' => 'Дата трека (формат: Год трека)',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required', 'date_format:Y'],
            ],
            'songText' => [
                'name' => 'songText',
                'description' => 'Песня трека',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required', 'min:1', 'max:6000'],
            ],
        ];
    }
}
