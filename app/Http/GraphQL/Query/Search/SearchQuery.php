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
    protected $attributes = [
        'name' => 'SearchQuery',
        'description' => 'Запрос поиска по трекам, альбомам, пользователям',
    ];

    public function type()
    {
        return \GraphQL::type('SearchType');
//        return [Type::listOf( \GraphQL::type('Album')),
//            Type::listOf( \GraphQL::type('Track')),
//            Type::listOf( \GraphQL::type('User')),
//
//        ];
    }

    public function args()
    {
        return [
            'q' => [
                'name' => 'q',
                'type' => Type::nonNull(Type::string()),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        if (isset($args['q'])) {
            $result = [];
            $s = App::make('SearchService');
            $rawResult = $s->search($args['q']);

            foreach ($rawResult as $type => $value) {
                $className = (new \ReflectionClass($type))->getShortName();
                $result[$className] = $value;
            }

            return $result;
        }

        return null;
    }
}
