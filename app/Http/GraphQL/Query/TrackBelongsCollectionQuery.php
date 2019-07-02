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

    public function resolve($root, $args, SelectFields $fields)
    {
        //die(json_encode($args));
        $track = Track::query()->find($args['trackId']);
//        foreach ($track->collections as $collection){
//            $ss[] = $collection;
//        }
        //die(json_encode($track->collections->contains($args['collectionId'])));
        return $track->collections->contains($args['collectionId']);
    }
}
