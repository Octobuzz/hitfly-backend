<?php

namespace App\Http\GraphQL\Query\Genre;

use App\Models\Genre;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class GenresQuery extends Query
{
    protected $attributes = [
        'name' => 'Genres Query',
        'description' => 'Список жанров системы с пагинацией',
    ];

    public function args()
    {
        return [
            'limit' => ['name' => 'limit', 'type' => Type::nonNull(Type::int())],
            'page' => ['name' => 'page', 'type' => Type::nonNull(Type::int())],
        ];
    }

    public function type()
    {
        return \GraphQL::paginate('Genre');
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        return Genre::with($fields->getRelations())->select($fields->getSelect())
            ->paginate($args['limit'], ['*'], 'page', $args['page']);
    }
}
