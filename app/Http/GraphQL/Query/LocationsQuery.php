<?php

namespace App\Http\GraphQL\Query;

use App\Models\City;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class LocationsQuery extends Query
{
    protected $attributes = [
        'name' => 'LocationQuery',
        'description' => 'Запрос списка городов',
    ];

    public function type()
    {
        return \GraphQL::paginate('CityType');
    }

    public function args()
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::listOf(Type::int()),
            ],
            'q' => [
                'name' => 'q',
                'description' => 'запрос по имени города',
                'type' => Type::string(),
            ],
            'limit' => ['name' => 'limit', 'type' => Type::nonNull(Type::int())],
            'page' => ['name' => 'page', 'type' => Type::nonNull(Type::int())],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        $query = City::query();
        if (!empty($args['q'])) {
            $query->where('title', 'like', $args['q'].'%');
        }
        if (!empty($args['id'])) {
            $query->whereIn('id', $args['id']);
        }
        $paginate = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

        return $paginate;
    }
}
