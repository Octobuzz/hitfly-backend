<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 01.03.19
 * Time: 13:29.
 */

namespace App\Http\GraphQL\Unions;

use App\Models\Album;
use App\Models\Collection;
use App\Models\Comment;
use App\Models\Lifehack;
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
            \GraphQL::type('Album'),
            \GraphQL::type('Track'),
            \GraphQL::type('Collection'),
            \GraphQL::type('LifehackType'),
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
        switch (get_class($value)) {
            case Track::class:
                return \GraphQL::type('Track');
                break;
            case Album::class:
                return \GraphQL::type('Album');
                break;
            case Collection::class:
                return \GraphQL::type('Collection');
                break;
            case Lifehack::class:
                return \GraphQL::type('LifehackType');
                break;
            default:
                throw new \Exception('exeption');
        }
    }
}
