<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 05.04.19
 * Time: 13:42.
 */

namespace App\Http\GraphQL\Mutations\Collection;

use App\Models\Collection;
use App\Models\Track;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;

class AddInCollectionMutation extends Mutation
{
    protected $attributes = [
        'name' => 'addToUserPlayList',
        'description' => 'Добавление треков в плейлист пользователя',
    ];

    public function type()
    {
        return \GraphQL::type('Collection');
    }

    public function args()
    {
        return [
            'tracksId' => [
                'type' => Type::nonNull(Type::listOf(Type::int())),
                'description' => 'Индетификаторы треков',
            ],
            'collectionId' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Id коллекции',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $tracks = Track::query()->whereIn('id', $args['tracksId'])->pluck('id');
        /** @var Collection $collection */
        $collection = Collection::query()->find($args['collectionId']);

        $collection->tracks()->syncWithoutDetaching($tracks);

        return $collection;
    }
}
