<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\Collection;
use App\Models\Track;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class RemoveTrackFromCollectionMutation extends Mutation
{
    protected $attributes = [
        'name' => 'RemoveTrackFromAlbumMutation',
        'description' => 'Удаление трека из альбома',
    ];

    public function type()
    {
        return \GraphQL::type('Collection');
    }

    public function args()
    {
        return [
            'trackId' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'id трека',
            ],
            'collectionId' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'id коллекции',
                'alias' => 'id',
                'rules' => 'remove_track_from_collection_validate',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        /** @var Track $track */
        $track = Track::query()->find($args['trackId']);
        $collection = Collection::query()->find($args['collectionId']);
        $collection->tracks()->detach($track);

        return $collection;
    }
}
