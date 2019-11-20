<?php

namespace App\Http\GraphQL\Mutations;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\Album;
use App\Models\Collection;
use App\Models\Favourite;
use App\Models\Track;
use Rebing\GraphQL\Support\Mutation;

class AddToFavouriteMutation extends Mutation
{
    use GraphQLAuthTrait;
    protected $attributes = [
        'name' => 'AddToFavourite',
        'description' => 'Добавить в избранное',
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
            case Favourite::TYPE_COLLECTION:
                $class = Collection::class;
                break;
            default:
                throw new \InvalidArgumentException('Не удалось определить тип избранного');
        }

        $tmp = [];
        $tmp['favouriteable_type'] = $class;
        $tmp['favouriteable_id'] = $args['Favourite']['favouriteableId'];
        $tmp['user_id'] = $this->getGuard()->user()->id;
        $favourite = Favourite::create($tmp);
        $favourite->save();

        return $favourite;
    }
}
