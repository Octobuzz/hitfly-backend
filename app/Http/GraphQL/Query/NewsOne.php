<?php

namespace App\Http\GraphQL\Query;

use App\Models\News;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

/**
 * Class AlbumQuery.
 */
class NewsOne extends Query
{
    protected $attributes = [
        'name' => 'News one',
        'description' => 'Запрос одной новости',
    ];

    public function type()
    {
        return \GraphQL::type('NewsType');
    }

    public function args()
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::nonNull(Type::int())],
        ];
    }

    public function resolve($root, $args)
    {
        if (isset($args['id'])) {
            return News::query()->where('id', $args['id'])->first();
        }

        return null;
    }
}
