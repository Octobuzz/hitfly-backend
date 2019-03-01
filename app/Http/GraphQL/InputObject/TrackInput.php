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
            'name' => [
                'name' => 'trackName',
                'description' => 'name',
                'type' => Type::string(),
                'rules' => ['max:250'],
            ],
            'album' => [
                'name' => 'album',
                'description' => 'album',
                'type' => Type::int(),
                'rules' => ['min:0', 'max:5'],
            ],
            'genre' => [
                'name' => 'description',
                'description' => 'description',
                'type' => Type::int(),
                'rules' => ['min:0', 'max:5'],
            ],
            'singer' => [
                'name' => 'singer',
                'description' => 'singer',
                'type' => Type::string(),
                'rules' => ['min:0', 'max:5'],
            ],
            'track_date' => [
                'name' => 'trackDate',
                'description' => 'track_date',
                'type' => Type::string(),
                'rules' => ['min:0', 'max:5'],
            ],
            'song_text' => [
                'name' => 'songText',
                'description' => 'description',
                'type' => Type::string(),
                'rules' => ['min:0', 'max:5'],
            ],
        ];
    }
}
