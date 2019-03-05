<?php

namespace App\Http\GraphQL\Mutations;

use GraphQL;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\UploadType;

class CreateCollectionMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UpdateTrack',
    ];

    public function type()
    {
        return GraphQL::type('Collection');
    }

    public function args()
    {
        return [
            'image' => [
                'name' => 'image',
                'type' => UploadType::getInstance(),
                'rules' => ['required', 'image', 'max:1500'],
            ],
            'collection' => [
                'type' => GraphQL::type('CollectionInput'),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $file = $args['track'];
        $user = \Auth::guard('json')->user();

        if (null === $user) {
            return JsonResponse::create();
        }

        Storage::putFileAs('public/collection/'.$user->id, $file, $file);
    }
}
