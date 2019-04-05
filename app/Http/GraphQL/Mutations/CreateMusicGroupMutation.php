<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\GroupLinks;
use App\Models\MusicGroup;
use App\User;
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
        /** @var User $user */
        $user = \Auth::guard('json')->user();

        $musicGroup = new MusicGroup();
        $musicGroup->setUser($user);
        //todo create redis for genre if found
        $musicGroup->name = $args['musicGroup']['name'];
        $musicGroup->career_start_year = $args['musicGroup']['careerStartYear'];
        $musicGroup->genre_id = $args['musicGroup']['genre'];
        $musicGroup->description = $args['musicGroup']['description'];

        $musicGroup->save();

        foreach ($args['musicGroup']['socialLinks'] as $social){
            $socialLinks = new GroupLinks();
            $socialLinks->social_type = $social['socialType'];
            $socialLinks->link = $social['link'];
            $socialLinks->music_group_id = $musicGroup->id;
            $socialLinks->save();
        }

        return $musicGroup;
    }
}
