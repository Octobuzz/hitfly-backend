<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\GroupLinks;
use App\Models\InviteToGroup;
use App\Models\MusicGroup;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Rebing\GraphQL\Error\ValidationError;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\UploadType;

class CreateMusicGroupMutation extends Mutation
{
    protected $attributes = [
        'name' => 'CreateMusicGroup',
        'description' => 'Создание музыкальной группы',
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
            'avatar' => [
                'description' => 'аватар музыкальной группы',
                'type' => UploadType::getInstance(),
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

        $countgroup = MusicGroup::query()->where('creator_group_id', '=', $user->id)->count();

        if ($countgroup >= 5) {
            return new ValidationError(trans('validation.groupMaxCount'));
        }

        $musicGroup = new MusicGroup();
        $musicGroup->setUser($user);
        //todo create redis for genre if found
        $musicGroup->name = $args['musicGroup']['name'];
        $musicGroup->career_start_year = $args['musicGroup']['careerStartYear'];
        $musicGroup->description = $args['musicGroup']['description'];
        if (!empty($args['avatar']) && null !== $args['avatar']) {
            $musicGroup->avatar_group = $this->setAvatar($musicGroup, $args['avatar']);
        }

        $musicGroup->save();

        if (!empty($args['musicGroup']['genre'])) {
            $musicGroup->genres()->sync($args['musicGroup']['genre']);
        }

        if (!empty($args['musicGroup']['socialLinks'])) {
            foreach ($args['musicGroup']['socialLinks'] as $social) {
                $socialLinks = new GroupLinks();
                $socialLinks->social_type = $social['socialType'];
                $socialLinks->link = $social['link'];
                $socialLinks->music_group_id = $musicGroup->id;
                $socialLinks->save();
            }
        }
        if (!empty($args['musicGroup']['invitedMembers'])) {
            foreach ($args['musicGroup']['invitedMembers'] as $members) {
                $inviteMember = new InviteToGroup();
                if (!empty($members['email'])) {
                    $inviteMember->email = $members['email'];
                }
                if (!empty($members['user_id'])) {
                    $inviteMember->user_id = $members['user_id'];
                }
                $inviteMember->music_group_id = $musicGroup->id;
                $inviteMember->save();
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
        $image_resize->fit(config('image.size.music_group.default.height'), config('image.size.music_group.default.height')/*, function ($constraint) {
            $constraint->aspectRatio();
        }*/);
        $path = Storage::disk('public')->getAdapter()->getPathPrefix();
        //создадим папку, если несуществует
        if (!file_exists($path.MusicGroup::PATH_FOLDER.DIRECTORY_SEPARATOR.$musicGroup->creator_group_id)) {
            Storage::disk('public')->makeDirectory(MusicGroup::PATH_FOLDER.DIRECTORY_SEPARATOR.$musicGroup->creator_group_id);
        }
        $image_resize->save($path.$imagePath, 100);

        return $imagePath;
    }
}
