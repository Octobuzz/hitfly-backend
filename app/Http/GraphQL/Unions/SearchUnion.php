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
use App\User;
use Rebing\GraphQL\Support\UnionType;

class SearchUnion extends UnionType
{
    protected $attributes = [
        'name' => 'SearchResult',
    ];

    public function types()
    {
        return [
            \GraphQL::type('Album'),
            \GraphQL::type('Track'),
            \GraphQL::type('User'),
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
        die(var_dump($value));
        switch ($value->favouriteable_type) {
            case Track::class:
                return \GraphQL::type('Track');
                break;
            case Album::class:
                return \GraphQL::type('Album');
                break;
            case User::class:
                return \GraphQL::type('User');
                break;
            default:
                throw new \Exception('exeption');
        }
    }
}
