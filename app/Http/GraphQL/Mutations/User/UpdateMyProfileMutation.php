<?php

namespace App\Http\GraphQL\Mutations\User;

use App\Helpers\DBHelpers;
use App\Models\ArtistProfile;
use App\Models\Genre;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\UploadType;
use Illuminate\Support\Facades\Storage;

class UpdateMyProfileMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UpdateMyProfile',
        'description' => 'Обновление профиля текущего пользователя',
    ];

    public function type()
    {
        return \GraphQL::type('MyProfile');
    }

    public function args()
    {
        return [
            'profile' => [
                'type' => \GraphQL::type('MyProfileInput'),
                'description' => 'профиль обычного пользователя',
            ],
            'artistProfile' => [
                'type' => \GraphQL::type('ArtistProfileInput'),
                'description' => 'профиль артиста',
            ],
            'avatar' => [
                'description' => 'аватар',
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
        $user = Auth::guard('json')->user();
        if (!empty($args['profile'])) {
            if (!empty($args['profile']['password']) && $args['profile']['password']) {
                $args['profile']['password'] = Hash::make($args['profile']['password']);
            }
            //аватар пользователя
            if (!empty($args['avatar']) && null !== $args['avatar']) {
                $user->avatar = $this->setAvatar($user, $args['avatar']);
            }

            $user->update(DBHelpers::arrayKeysToSnakeCase($args['profile']));
            $user->save();

            if (!empty($args['profile']['genres'])) {
                $tmpGenres = [];
                $genres = Genre::query()->findMany($args['profile']['genres']);
                foreach ($genres as $genre) {
                    $tmpGenres[] = $genre->id;
                }
                $user->favouriteGenres()->sync($tmpGenres);
            }
        }

        if (!empty($args['artistProfile'])) {
            $artist = $user->artistProfile;
            if (null === $artist) {
                $artist = new ArtistProfile();
                $artist->user()->associate($user);
            }
            if (isset($args['artistProfile']['description'])) {
                $artist->description = $args['artistProfile']['description'];
            }
            if (isset($args['artistProfile']['careerStart'])) {
                $artist->career_start = $args['artistProfile']['careerStart'];
            }
            if (!empty($args['artistProfile']['genres'])) {
                $tmpGenres = [];
                $genres = Genre::query()->findMany($args['artistProfile']['genres']);
                foreach ($genres as $genre) {
                    $tmpGenres[] = $genre->id;
                }
                $artist->genres()->sync($tmpGenres);
            }
            $artist->save();
        }

        return $user;
    }

    /**
     * добавление аватарки пользователя.
     *
     * @param $user
     * @param $avatar
     *
     * @return string
     */
    private function setAvatar($user, $avatar)
    {
        //удаление старых аватарок
        if (null !== $user->getOriginal('avatar')) {
            Storage::disk('public')->delete($user->getOriginal('avatar'));
        }
        $image = $avatar;
        $nameFile = md5(microtime());
        $imagePath = "avatars/$user->id/".$nameFile.'.'.$image->getClientOriginalExtension();
        $image_resize = Image::make($image->getRealPath());
        $image_resize->fit(config('image.size.avatar.default.width'), config('image.size.avatar.default.height')/*, function ($constraint) {
            $constraint->aspectRatio();
        }*/);
        $path = Storage::disk('public')->getAdapter()->getPathPrefix();
        //создадим папку, если несуществует
        if (!file_exists($path.'avatars/'.$user->id)) {
            Storage::disk('public')->makeDirectory('avatars/'.$user->id);
        }
        $image_resize->save($path.$imagePath, 100);

        return $imagePath;
    }
}
