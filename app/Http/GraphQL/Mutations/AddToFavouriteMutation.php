<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\Album;
use App\Models\Favourite;
use App\Models\Genre;
use App\Models\Track;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Mutation;

class AddToFavouriteMutation extends Mutation
{
    protected $attributes = [
        'name' => 'AddToFavourite',
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
                'rules' => 'favourites_unique_validate',
            ],
        ];
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
            case Favourite::TYPE_GENRE:
                $class = Genre::class;
                break;
            default:
                throw new \Exception('Не удалось определить тип избранного');
        }

        $tmp = [];
        $tmp['favouriteable_type'] = $class;
        $tmp['favouriteable_id'] = $args['Favourite']['favouriteableId'];
        $tmp['user_id'] = Auth::user()->id;
        $favourite = Favourite::create($tmp);
        $favourite->save();

        return $favourite;
    }
}
