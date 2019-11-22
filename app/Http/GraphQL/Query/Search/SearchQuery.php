<?php

namespace App\Http\GraphQL\Query\Search;

use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\App;
use Rebing\GraphQL\Support\Query;

/**
 * Class AlbumQuery.
 */
class SearchQuery extends Query
{
    private const DEFAULT_QUERY_LIMIT = 3;
    protected $attributes = [
        'name' => 'SearchQuery',
        'description' => 'Запрос поиска по трекам, альбомам, пользователям',
    ];

    public function type()
    {
        return \GraphQL::type('SearchType');
    }

    public function args()
    {
        return [
            'query' => [
                'name' => 'query',
                'description' => 'поисковый запрос',
                'type' => Type::nonNull(Type::string()),
            ],
            'limit' => [
                'limit' => 'limit',
                'description' => 'ограничение количества результатов',
                'type' => Type::int(),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        if (isset($args['query'])) {
            $result = [];
            $searchService = App::make('SearchService');
            if (!empty($args['limit'])) {
                $limit = $args['limit'];
            } else {
                $limit = self::DEFAULT_QUERY_LIMIT;
            }
            $rawResult = $searchService->search($args['query'], $limit);

            foreach ($rawResult as $type => $value) {
                $className = (new \ReflectionClass($type))->getShortName();
                $result[$className] = $value;
            }

            return $result;
        }

        return null;
    }
}
