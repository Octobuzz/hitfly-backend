<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\MusicGroup;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class DeleteMusicGroupMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UpdateMusicGroup',
        'description' => 'Удаление музыкальной группы',
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

        $musicGroup = MusicGroup::query()->find($args['id']);

        if (null === $musicGroup) {
            throw new \Exception('inncorect');
        }

        if ($user->can('deleted', $musicGroup)) {
            $musicGroup->delete();
        }

        return null;
    }
}
