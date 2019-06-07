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
            'singer' => [
                'name' => 'singer',
                'description' => 'Испольнитель',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required', 'min:0', 'max:250'],
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
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required', 'date_format:Y'],
            ],
            'songText' => [
                'name' => 'songText',
                'description' => 'Песня трека',
                'type' => Type::nonNull(UploadType::getInstance()),
                'rules' => ['max:6000', new UploadDocAndTxtFile()],
            ],
        ];
    }
}
