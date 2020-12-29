<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 01.03.19
 * Time: 13:29.
 */

namespace App\Http\GraphQL\Unions;

use App\Models\Comment;
use App\Models\Track;
use App\User;
use Rebing\GraphQL\Support\UnionType;

class AttributesUnion extends UnionType
{
    protected $attributes = [
        'name' => 'AttributeResult',
    ];

    public function types()
    {
        return [
            \GraphQL::type('User'),
            \GraphQL::type('Track'),
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
            case User::class:
                return \GraphQL::type('User');
                break;
            default:
                throw new \Exception('Не могу определить класс (AttributeResult)');
        }
    }
}
