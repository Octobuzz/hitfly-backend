<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\Album;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class DeleteAlbumMutation extends Mutation
{
    protected $attributes = [
        'name' => 'DeleteAlbumMutation',
        'description' => 'Удаление своего альбома',
    ];

    public function type()
    {
        return \GraphQL::type('Album');
    }

    public function args()
    {
        return [
            'albumId' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'id альбома',
                'rules' => 'album_delete_validate',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $album = Album::query()->find($args['albumId']);
        $album->delete();

        return null;
    }
}
