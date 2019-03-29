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

class FavouriteUnion extends UnionType
{
    protected $attributes = [
        'name' => 'FavouriteResult',
    ];

    public function types()
    {
        return [
            \GraphQL::type('FavouriteAlbum'),
            \GraphQL::type('FavouriteTrack'),
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
        switch ($value->favouriteable_type) {
            case Track::class:
                return \GraphQL::type('FavouriteTrack');
                break;
            case Album::class:
                return \GraphQL::type('FavouriteAlbum');
                break;
            default:
                throw new \Exception('exeption');
        }
    }
}
