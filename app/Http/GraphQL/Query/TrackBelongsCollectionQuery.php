<?php

namespace App\Http\GraphQL\Query;

use App\Models\Track;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

/**
 * Class AlbumQuery.
 */
class TrackBelongsCollectionQuery extends Query
{
    protected $attributes = [
        'name' => 'TrackBelongsCollectionQuery',
        'description' => 'Принадлежит ли трек коллекции',
    ];

    public function type()
    {
        return \GraphQL::type('TrackBelongsCollection');
    }

    public function args()
    {
        return [
            'trackId' => [
                'type' => Type::int(),
                'description' => 'ID трека',
            ],
            'collectionId' => [
                'type' => Type::int(),
                'description' => 'ID Коллекции',
            ],
        ];
    }

    /**
     * @param $root
     * @param $args
     * @param SelectFields $fields
     *
     * @return bool
     */
    public function resolve($root, $args, SelectFields $fields)
    {
        $track = Track::query()->find($args['trackId']);
        if (null === $track) {
            return false;
        }

        return $track->collections->contains($args['collectionId']);
    }
}
