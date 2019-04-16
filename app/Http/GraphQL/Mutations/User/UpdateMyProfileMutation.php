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
                'type' => Type::nonNull(\GraphQL::type('MyProfileInput')),
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

    public function resolve($root, $args)
    {
        $user = Auth::user();
        if (!empty($args['profile'])) {
            if ($args['profile']['password']) {
                $args['profile']['password'] = Hash::make($args['profile']['password']);
            }
            //аватар пользователя
            if ($args['avatar'] !== null) {
                //todo удаление старых аватарок

                $image = $args['avatar'];
                $nameFile = md5(microtime());
                $imagePath = "avatars/$user->id/".$nameFile.'.'.$image->getClientOriginalExtension();
                $image_resize = Image::make($image->getRealPath());
                $image_resize->resize(config('image.size.avatar.width'), config('image.size.avatar.height'),function ($constraint) {
                    $constraint->aspectRatio();
                });
                $path = Storage::disk('public')->getAdapter()->getPathPrefix();
                $image_resize->save($path.$imagePath);

                $user->avatar = $imagePath;

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
            }
            $artist->update(DBHelpers::arrayKeysToSnakeCase($args['artistProfile']));

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
}
