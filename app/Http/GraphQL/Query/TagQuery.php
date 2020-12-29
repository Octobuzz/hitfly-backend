<?php

namespace App\Http\GraphQL\Query;

use App\Models\Tag;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

/**
 * Class AlbumQuery.
 */
class TagQuery extends Query
{
    protected $attributes = [
        'name' => 'TagQuery',
        'description' => 'Запрос тегов',
    ];

    public function type()
    {
        return \GraphQL::paginate('TagType');
    }

    public function args()
    {
        return [
            'limit' => ['name' => 'limit', 'type' => Type::nonNull(Type::int())],
            'page' => ['name' => 'page', 'type' => Type::nonNull(Type::int())],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        $query = $query = Tag::with($fields->getRelations())
            ->orderBy('name', 'ASC');

        $response = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

        return $response;
    }
}
