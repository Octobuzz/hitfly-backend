<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\Album;
use App\Models\Collection;
use App\Models\Favourite;
use App\Models\Track;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Mutation;

class DeleteFromFavouriteMutation extends Mutation
{
    protected $attributes = [
        'name' => 'DeleteFromFavourite',
        'description' => 'Удалить из избранного',
    ];

    public function type()
    {
        return \GraphQL::type('FavouriteResult');
    }

    public function args()
    {
        return [
            'Favourite' => [
                'type' => \GraphQL::type('FavouriteInput'),
                'rules' => 'favourites_delete_validate',
            ],
        ];
    }

    public function authorize(array $args)
    {
        return Auth::check();
    }

    public function resolve($root, $args)
    {
        switch ($args['Favourite']['favouritableType']) {
            case Favourite::TYPE_TRACK:
                $class = Track::class;
                break;
            case Favourite::TYPE_ALBUM:
                $class = Album::class;
                break;
            case Favourite::TYPE_COLLECTION:
                $class = Collection::class;
                break;
            default:
                throw new \Exception('Не удалось определить тип избранного');
        }

        $user = Auth::user();

        $favourite = Favourite::query()
            ->where('favouriteable_id', $args['Favourite']['favouriteableId'])
            ->where('favouriteable_type', $class)
            ->where('user_id', $user->id)
            ->first();

        if (null === $favourite) {
            throw new \Exception('inncorect');
        } else {
            $favourite->delete();
        }

        return null;
    }
}
