<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\MusicGroup;
use Rebing\GraphQL\Support\Mutation;

class CreateMusicGroupMutation extends Mutation
{
    protected $attributes = [
        'name' => 'CreateMusicGroup',
    ];

    public function type()
    {
        return \GraphQL::type('MusicGroup');
    }

    public function args()
    {
        return [
            'musicGroup' => [
                'type' => \GraphQL::type('MusicGroupInput'),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $musicGroup = MusicGroup::create($args);
        $musicGroup->save();

        return $musicGroup;
    }
}
