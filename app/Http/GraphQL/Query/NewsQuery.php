<?php

namespace App\Http\GraphQL\Query;

use App\Models\News;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

/**
 * Class AlbumQuery.
 */
class NewsQuery extends Query
{
    protected $attributes = [
        'name' => 'NewsQuery',
        'description' => 'Новости',
    ];

    public function type()
    {
        return \GraphQL::paginate('NewsType');
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
        if ($args['limit'] > 100) {
            $args['limit'] = 100;
        }
        $query = News::with($fields->getRelations())
            ->orderBy('created_at', 'DESC')
        ;

        $response = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

        return $response;
    }
}
