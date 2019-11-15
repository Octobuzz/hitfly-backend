<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\Track;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Mutation;

class RemoveTrackFromAlbumMutation extends Mutation
{
    protected $attributes = [
        'name' => 'RemoveTrackFromAlbumMutation',
        'description' => 'Удаление трека из альбома',
    ];

    public function type()
    {
        return \GraphQL::type('Track');
    }

    public function args()
    {
        return [
            'trackId' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'id трека',
            ],
            'albumId' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'id альбома',
                'alias' => 'id',
                'rules' => 'remove_track_from_album_validate',
            ],
        ];
    }

    public function authorize(array $args)
    {
        return Auth::check();
    }

    public function resolve($root, $args)
    {
        /** @var Track $track */
        $track = Track::query()->find($args['trackId']);
        $track->album_id = null;
        $track->save();

        return null;
    }
}
