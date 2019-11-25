<?php

namespace App\Http\GraphQL\Mutations;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\MusicGroup;
use App\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class DeleteMusicGroupMutation extends Mutation
{
    use GraphQLAuthTrait;
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
                'rules' => 'music_group_delete_validate',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        /** @var User $user */
        $user = $this->getGuard()->user();

        $musicGroup = MusicGroup::query()->find($args['id']);
        $musicGroup->delete();

        return null;
    }
}
