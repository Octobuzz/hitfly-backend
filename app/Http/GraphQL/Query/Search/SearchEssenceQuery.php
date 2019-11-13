<?php

namespace App\Http\GraphQL\Query\Search;

use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\App;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

/**
 * Class SearchEssenceQuery.
 */
class SearchEssenceQuery extends Query
{
    protected $attributes = [
        'name' => 'SearchEssenceQuery',
        'description' => 'Поиск по одной сущности',
    ];

    public function type()
    {
        return \GraphQL::paginate('SearchResult');
    }

    public function args()
    {
        return [
            'q' => [
                'name' => 'q',
                'description' => 'поисковый запрос',
                'type' => Type::nonNull(Type::string()),
            ],
            'essence' => [
                'limit' => 'essence',
                'description' => 'Сущность',
                'type' => Type::nonNull(\GraphQL::type('SearchTypeEnum')),
            ],
            'limit' => ['name' => 'limit', 'type' => Type::nonNull(Type::int())],
            'page' => ['name' => 'page', 'type' => Type::nonNull(Type::int())],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        if (isset($args['q'])) {
            $result = [];
            $s = App::make('SearchService');
            $result = $s->searchEssence($args, $fields);

            return $result;
        }

        return null;
    }
}
