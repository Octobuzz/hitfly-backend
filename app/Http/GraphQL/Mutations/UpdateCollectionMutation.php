<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 05.03.19
 * Time: 15:17.
 */

namespace App\Http\GraphQL\Mutations;

use GraphQL\Type\Definition\Type;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\UploadType;

class UpdateCollectionMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UpdateCollection',
    ];

    public function type()
    {
        return \GraphQL::type('Collection');
    }

    public function args()
    {
        return [
            'id' => [
                'name' => 'image',
                'type' => Type::nonNull(Type::int()),
                'rules' => ['required', 'integer'],
            ],
            'image' => [
                'name' => 'image',
                'type' => UploadType::getInstance(),
                'rules' => ['required', 'image', 'max:1500'],
            ],
            'collection' => [
                'type' => \GraphQL::type('CollectionInput'),
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
