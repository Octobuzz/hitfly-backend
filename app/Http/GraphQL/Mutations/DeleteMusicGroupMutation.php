<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\MusicGroup;
use App\User;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
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
                'rules' => 'music_group_delete_validate',
            ],
        ];
    }

    public function authorize(array $args)
    {
        return Auth::guard('json')->check();
    }

    public function resolve($root, $args)
    {
        /** @var User $user */
        $user = Auth::guard('json')->user();

        $musicGroup = MusicGroup::query()->find($args['id']);
        $musicGroup->delete();

        return null;
    }
}
