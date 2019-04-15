<?php

namespace App\Http\GraphQL\Mutations\User;

use App\Helpers\DBHelpers;
use App\Models\ArtistProfile;
use App\Models\Genre;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Rebing\GraphQL\Support\Mutation;

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
        ];
    }

    public function resolve($root, $args)
    {
        $user = Auth::user();
        if (!empty($args['profile'])) {
            if ($args['profile']['password']) {
                $args['profile']['password'] = Hash::make($args['profile']['password']);
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
