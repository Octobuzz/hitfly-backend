<?php

namespace App\Http\GraphQL\Mutations;

use GraphQL;
use Rebing\GraphQL\Support\UploadType;
use Rebing\GraphQL\Support\Mutation;

class TrackUploadMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UpdateTrack',
    ];

    public function type()
    {
        return GraphQL::type('Track');
    }

    public function args()
    {
        return [
            'track' => [
                'name' => 'track',
                'type' => UploadType::getInstance(),
                'rules' => ['required', 'image', 'max:1500'],
            ],
            'info_track' => [
                'type' => \GraphQL::type('TrackInput'),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $file = $args['track'];
        dd(get_class($file));

        // Do something with file here...
    }
}
