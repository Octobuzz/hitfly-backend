<?php

namespace App\Http\GraphQL\Mutations;

use App\Helpers\DBHelpers;
use App\Models\GroupLinks;
use App\Models\InviteToGroup;
use App\Models\MusicGroup;
use App\Rules\AuthorUpdateMusicGroup;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\UploadType;

class UpdateMusicGroupMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UpdateMusicGroup',
        'description' => 'обновление музыкальной группы',
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
                'description' => 'ID группы',
                'rules' => [new AuthorUpdateMusicGroup()],
            ],
            'musicGroup' => [
                'type' => \GraphQL::type('MusicGroupInput'),
            ],
            'avatar' => [
                'description' => 'аватар музыкальной группы',
                'type' => UploadType::getInstance(),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $musicGroup = MusicGroup::find($args['id']);

        if (null === $musicGroup) {
            return null;
        }

        if (!empty($args['avatar']) && null !== $args['avatar']) {
            $musicGroup->avatar_group = $this->setAvatar($musicGroup, $args['avatar']);
            $musicGroup->save();
        }
        if (!empty($args['musicGroup']) && null !== $args['musicGroup']) {
            $musicGroup->update(DBHelpers::arrayKeysToSnakeCase($args['musicGroup']));
        }

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

    /**
     * добавление аватарки группы.
     *
     * @param $musicGroup
     * @param $avatar
     *
     * @return string
     */
    private function setAvatar($musicGroup, $avatar)
    {
        //удаление старых аватарок
        if (null !== $musicGroup->getOriginal('avatar_group')) {
            Storage::disk('public')->delete($musicGroup->getOriginal('avatar_group'));
        }
        $image = $avatar;
        $nameFile = md5(microtime());
        $imagePath = "music_groups/$musicGroup->creator_group_id/".$nameFile.'.'.$image->getClientOriginalExtension();
        $image_resize = Image::make($image->getRealPath());
        $image_resize->resize(config('image.size.music_group.default.height'), config('image.size.music_group.default.height'), function ($constraint) {
            $constraint->aspectRatio();
        });
        $path = Storage::disk('public')->getAdapter()->getPathPrefix();
        //создадим папку, если несуществует
        if (!file_exists($path.'music_groups/'.$musicGroup->creator_group_id)) {
            Storage::disk('public')->makeDirectory('music_groups/'.$musicGroup->creator_group_id);
        }
        $image_resize->save($path.$imagePath);

        return $imagePath;
    }
}
