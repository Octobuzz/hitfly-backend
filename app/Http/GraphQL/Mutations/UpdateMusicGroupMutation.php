<?php

namespace App\Http\GraphQL\Mutations;

use App\Helpers\DBHelpers;
use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\InviteToGroup;
use App\Models\MusicGroup;
use App\Rules\AuthorUpdateMusicGroup;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\UploadType;

class UpdateMusicGroupMutation extends Mutation
{
    use GraphQLAuthTrait;
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
                'type' => \GraphQL::type('MusicGroupUpdateInput'),
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
            $sync = DBHelpers::arrayKeysToSnakeCase($args['musicGroup']['socialLinks']);
            foreach ($sync as $k => $v) {
                $sync[$k]['music_group_id'] = $args['id'];
            }

            $musicGroup->socialLinks()->sync($sync, true);
        } else {
            $musicGroup->socialLinks()->sync([], true);
        }
        if (!empty($args['musicGroup']['invitedMembers'])) {
            foreach ($args['musicGroup']['invitedMembers'] as $members) {
                $inviteQuery = InviteToGroup::query()->where('music_group_id', $args['id']);

                if (!empty($members['email'])) {
                    $inviteQuery->where('email', '=', $members['email']);
                } elseif (!empty($members['user_id'])) {
                    $inviteQuery->where('user_id', '=', $members['user_id']);
                }
                $inviteMember = $inviteQuery->first();

                if (null === $inviteMember) {
                    $inviteMember = new InviteToGroup();
                    if (!empty($members['email'])) {
                        $inviteMember->email = $members['email'];
                    } elseif (!empty($members['user_id'])) {
                        $inviteMember->user_id = $members['user_id'];
                    }
                    $inviteMember->music_group_id = $args['id'];
                    $inviteMember->save();
                }
            }
        }
        if (!empty($args['musicGroup']['activeMembers'])) {//удаление пользователей из группы невошедших в массив
            $inviteQuery = InviteToGroup::query()->where('music_group_id', $musicGroup->id)
                ->where('accept', '=', 1)
                ->where('user_id', '!=', null)->get();
            foreach ($inviteQuery as $member) {
                if (!in_array($member->user_id, $args['musicGroup']['activeMembers'])) {
                    $member->delete();
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
        $imagePath = MusicGroup::PATH_FOLDER.DIRECTORY_SEPARATOR."$musicGroup->creator_group_id/".$nameFile.'.'.$image->getClientOriginalExtension();
        $image_resize = Image::make($image->getRealPath());
        $image_resize->resize(config('image.size.music_group.default.height'), config('image.size.music_group.default.height'), function ($constraint) {
            $constraint->aspectRatio();
        });
        $path = Storage::disk('public')->getAdapter()->getPathPrefix();
        //создадим папку, если несуществует
        if (!file_exists($path.MusicGroup::PATH_FOLDER.DIRECTORY_SEPARATOR.$musicGroup->creator_group_id)) {
            Storage::disk('public')->makeDirectory(MusicGroup::PATH_FOLDER.DIRECTORY_SEPARATOR.$musicGroup->creator_group_id);
        }
        $image_resize->save($path.$imagePath, 100);

        return $imagePath;
    }
}
