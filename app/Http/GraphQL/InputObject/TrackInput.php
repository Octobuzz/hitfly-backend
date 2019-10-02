<?php

namespace App\Http\GraphQL\InputObject;

use App\Rules\OwnerAlbum;
use App\Rules\OwnerMusicGroup;
use App\Rules\UploadDocAndTxtFile;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\UploadType;

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
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required', 'max:250'],
            ],
            'album' => [
                'name' => 'album',
                'description' => 'Альбом трека',
                'type' => Type::int(),
                'rules' => ['nullable', 'integer', new OwnerAlbum()],
            ],
            'genres' => [
                'name' => 'genres',
                'description' => 'Жанры трека',
                'type' => Type::nonNull(Type::listOf(Type::int())),
                'rules' => ['required', 'array', 'min:1', 'max:5'],
            ],
            'musicGroup' => [
                'name' => 'musicGroup',
                'description' => 'Музыкальная группа, к которой принадлежит трек',
                'type' => Type::int(),
                'rules' => ['nullable', 'integer', new OwnerMusicGroup()],
            ],
            'trackDate' => [
                'name' => 'trackDate',
                'description' => 'Дата трека (формат: Год трека)',
                'type' => Type::string(),
                'rules' => ['nullable', 'date_format:Y'],
            ],
            'songText' => [
                'name' => 'songText',
                'description' => 'Песня трека',
                'type' => UploadType::getInstance(),
                'rules' => ['nullable', 'max:6000', new UploadDocAndTxtFile()],
            ],
            'singer' => [
                'name' => 'singer',
                'description' => 'На удаление, больше не использется',
                'deprecationReason' => 'На удаление, больше не использется', //todo На удлание
            ],
            'cover' => [
                'name' => 'cover',
                'description' => 'Обложка трека',
                'type' => UploadType::getInstance(),
            ],
        ];
    }
}
