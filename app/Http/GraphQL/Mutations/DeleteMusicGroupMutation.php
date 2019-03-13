<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\MusicGroup;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class DeleteMusicGroupMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UpdateMusicGroup',
    ];

    public function type()
    {
        return Type::getNullableType(\GraphQL::type('MusicGroup'));
    }

    public function args()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the human.',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $user = \Auth::guard('json')->user();

        $musicGroup = MusicGroup::query()->find($args['id'])->first();

        if (null === $musicGroup) {
            throw new \Exception('inncorect');
        }

        if ($user->can('deleted', $musicGroup)) {
            $musicGroup->delete();
        }

        return null;
    }
}