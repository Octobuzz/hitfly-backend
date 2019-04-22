<?php

namespace App\Http\GraphQL\InputObject;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class TrackInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'TrackInput',
        'description' => 'Add new music group',
    ];

    public function fields()
    {
        return [
            'trackName' => [
                'name' => 'trackName',
                'description' => 'trackName',
                'type' => Type::string(),
                'rules' => ['max:250'],
            ],
            'album' => [
                'name' => 'album',
                'description' => 'album',
                'type' => Type::int(),
                'rules' => ['integer'],
            ],
            'genre' => [
                'name' => 'genre',
                'description' => 'genre',
                'type' => Type::int(),
                'rules' => ['integer'],
            ],
            'singer' => [
                'name' => 'singer',
                'description' => 'singer',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['min:0', 'max:250'],
            ],
            'trackDate' => [
                'name' => 'trackDate',
                'description' => 'track_date',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['date', 'required'],
            ],
            'songText' => [
                'name' => 'songText',
                'description' => 'description',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['min:0', 'max:6000'],
            ],
        ];
    }
}
