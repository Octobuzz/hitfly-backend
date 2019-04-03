<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 05.03.19
 * Time: 14:58.
 */

namespace App\Http\GraphQL\Query;

use App\Models\Collection;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class CollectionsQuery extends Query
{
    protected $attributes = [
        'name' => 'Collections Query',
    ];

    public function type()
    {
        return \GraphQL::paginate('Collection');
    }

    public function args()
    {
        return [
            'limit' => ['name' => 'limit', 'type' => Type::nonNull(Type::int())],
            'page' => ['name' => 'page', 'type' => Type::nonNull(Type::int())],
            'my' => [
                'name' => 'my',
                'type' => Type::boolean(),
                'description' => 'Только мои коллекции',
            ],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        if (false === empty($args['my']) && true === $args['my'] && null !== \Auth::user()) {
            return Track::with($fields->getRelations())->select($fields->getSelect())
                ->where('user_id', '=', \Auth::user()->id)
                ->paginate($args['limit'], ['*'], 'page', $args['page']);
        }

        return Collection::with($fields->getRelations())->select($fields->getSelect())
            ->paginate($args['limit'], ['*'], 'page', $args['page']);
    }
}
