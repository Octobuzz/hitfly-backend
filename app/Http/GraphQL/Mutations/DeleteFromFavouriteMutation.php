<?php

namespace App\Http\GraphQL\Mutations;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\Album;
use App\Models\Collection;
use App\Models\Favourite;
use App\Models\Track;
use GraphQL\Type\Definition\Type;
use http\Exception\InvalidArgumentException;
use Rebing\GraphQL\Support\Mutation;

class DeleteFromFavouriteMutation extends Mutation
{
    use GraphQLAuthTrait;
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
                throw new InvalidArgumentException('Не удалось определить тип избранного');
        }

        $user = $this->getGuard()->user();

        $favourite = Favourite::query()
            ->where('favouriteable_id', $args['Favourite']['favouriteableId'])
            ->where('favouriteable_type', $class)
            ->where('user_id', $user->id)
            ->first();
        $entity = $favourite->belongsTo($class, 'favouriteable_id')->first();
        if (null === $favourite) {
            throw new \Exception('inncorect');
        } else {
            $favourite->delete();
        }

        return $entity;
    }
}
