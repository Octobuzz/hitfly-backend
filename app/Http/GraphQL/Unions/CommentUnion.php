<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 01.03.19
 * Time: 13:29.
 */

namespace App\Http\GraphQL\Unions;

use App\Models\Album;
use App\Models\Comment;
use App\Models\Track;
use Rebing\GraphQL\Support\UnionType;

class CommentUnion extends UnionType
{
    protected $attributes = [
        'name' => 'CommentResult',
    ];

    public function types()
    {
        return [
            \GraphQL::type('CommentAlbum'),
            \GraphQL::type('CommentTrack'),
        ];
    }

    /**
     * @param Comment $value
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function resolveType($value)
    {
        switch (get_class($value->commentable())) {
            case Track::class:
                return \GraphQL::type('CommentTrack');
                break;
            case Album::class:
                return \GraphQL::type('CommentAlbum');
                break;
            default:
                throw new \Exception('exeption');
        }
    }
}
