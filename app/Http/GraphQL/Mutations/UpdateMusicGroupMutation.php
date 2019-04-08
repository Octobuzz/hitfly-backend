<?php

namespace App\Http\GraphQL\Mutations;

use App\Helpers\DBHelpers;
use App\Models\GroupLinks;
use App\Models\InviteToGroup;
use App\Models\MusicGroup;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Validator;
use Rebing\GraphQL\Support\Mutation;
use Symfony\Component\Finder\Exception\OperationNotPermitedException;

class UpdateMusicGroupMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UpdateMusicGroup',
    ];

    public function type()
    {
        return \GraphQL::type('MusicGroup');
    }

    public function args()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the human.',
            ],
            'musicGroup' => [
                'type' => \GraphQL::type('MusicGroupInput'),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $musicGroup = MusicGroup::find($args['id']);

        if (null === $musicGroup) {
            return null;
        }

        //if ($user = \Auth::user()->can('update', MusicGroup::class)) {
            //throw new OperationNotPermitedException('not persmision'); todo: неработает
        //}

        $musicGroup->update(DBHelpers::arrayKeysToSnakeCase($args['musicGroup']));


        foreach ($args['musicGroup']['socialLinks'] as $social){
            /** @var GroupLinks $socialLinks */
            $socialLinks = GroupLinks::query()->where('music_group_id',$args['id'])
                                        ->where('social_type',$social['socialType'])->first();
            if (null === $socialLinks) {
                $socialLinks = new GroupLinks();
                $socialLinks->social_type = $social['socialType'];
                $socialLinks->link = $social['link'];
                $socialLinks->music_group_id = $args['id'];
                $socialLinks->save();
            }else {
                $socialLinks->setRawAttributes(DBHelpers::arrayKeysToSnakeCase($social));
                $socialLinks->save();
            }
        }

        foreach ($args['musicGroup']['invitedMembers'] as $members){
            //Validator::make($members,['email'=>"required_without:user_id","user_id"=>"required_without:email"])->validate();
            $inviteQuery = InviteToGroup::query()->where('music_group_id',$args['id']);

            if(!empty($members['email'])) {
                $inviteQuery->where('email', '=', $members['email']);

            }
            if(!empty($members['user_id'])) {
                $inviteQuery->where('user_id', '=', $members['user_id']);

            }
            $inviteMember = $inviteQuery->first();

            if (null === $inviteMember) {
                $inviteMember = new InviteToGroup();
                if(!empty($members['email']))
                    $inviteMember->email = $members['email'];
                if(!empty($members['user_id']))
                    $inviteMember->user_id = $members['user_id'];
                $inviteMember->music_group_id = $args['id'];
                $inviteMember->save();
            }else {
                $inviteMember->setRawAttributes(DBHelpers::arrayKeysToSnakeCase($members));
                $inviteMember->save();
            }
        }

        return $musicGroup;
    }
}
