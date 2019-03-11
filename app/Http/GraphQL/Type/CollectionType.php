<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 05.03.19
 * Time: 14:03.
 */

namespace App\Http\GraphQL\Type;

use App\Models\Track;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CollectionType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Collection',
        'model' => Track::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
            ],
            'image' => [
                'type' => Type::string(),
            ],
            'title' => [
                'type' => Type::nonNull(Type::string()),
            ],
            'user' => [
                'type' => GraphQL::type('User'),
            ],
            'is_admin' => [
                'type' => Type::int(),
            ],
            'tracks' => [
                'type' => GraphQL::type('Track'),
            ],
        ];
    }
}
