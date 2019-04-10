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
        $musicGroup->description = $args['musicGroup']['description'];

        $musicGroup->save();

        if (!empty($args['musicGroup']['genre'])) {
            $musicGroup->genres()->sync($args['musicGroup']['genre']);
        }

        if (!empty($args['musicGroup']['socialLinks'])) {
            foreach ($args['musicGroup']['socialLinks'] as $social) {
                /** @var GroupLinks $socialLinks */
                $socialLinks = GroupLinks::query()->where('music_group_id', $args['id'])
                    ->where('social_type', $social['socialType'])->first();
                if (null === $socialLinks) {
                    $socialLinks = new GroupLinks();
                    $socialLinks->social_type = $social['socialType'];
                    $socialLinks->link = $social['link'];
                    $socialLinks->music_group_id = $args['id'];
                    $socialLinks->save();
                } else {
                    $socialLinks->setRawAttributes(DBHelpers::arrayKeysToSnakeCase($social));
                    $socialLinks->save();
                }
            }
        }
        if (!empty($args['musicGroup']['invitedMembers'])) {
            foreach ($args['musicGroup']['invitedMembers'] as $members) {
                //Validator::make($members,['email'=>"required_without:user_id","user_id"=>"required_without:email"])->validate();
                $inviteQuery = InviteToGroup::query()->where('music_group_id', $args['id']);

                if (!empty($members['email'])) {
                    $inviteQuery->where('email', '=', $members['email']);
                }
                if (!empty($members['user_id'])) {
                    $inviteQuery->where('user_id', '=', $members['user_id']);
                }
                $inviteMember = $inviteQuery->first();

                if (null === $inviteMember) {
                    $inviteMember = new InviteToGroup();
                    if (!empty($members['email'])) {
                        $inviteMember->email = $members['email'];
                    }
                    if (!empty($members['user_id'])) {
                        $inviteMember->user_id = $members['user_id'];
                    }
                    $inviteMember->music_group_id = $args['id'];
                    $inviteMember->save();
                } else {
                    $inviteMember->setRawAttributes(DBHelpers::arrayKeysToSnakeCase($members));
                    $inviteMember->save();
                }
            }
        }

        return $musicGroup;
    }
}
