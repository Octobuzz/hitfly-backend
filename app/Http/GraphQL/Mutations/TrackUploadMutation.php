<?php

namespace App\Http\GraphQL\Mutations;

use GraphQL;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
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
        $user = \Auth::guard('json')->user();

        if($user === null){
            return JsonResponse::create();
        }

        Storage::putFileAs('public/music/'.$user->id, $file, $file);

    }
}
