<?php

namespace App\Http\GraphQL\Query;

use App\Models\Lifehack;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

/**
 * Class AlbumQuery.
 */
class LifehacksQuery extends Query
{
    protected $attributes = [
        'name' => 'LifehacksQuery',
        'description' => 'Запрос лайфхаков',
    ];

    public function type()
    {
        return \GraphQL::paginate('LifehackType');
    }

    public function args()
    {
        return [
            'limit' => ['name' => 'limit', 'type' => Type::nonNull(Type::int())],
            'page' => ['name' => 'page', 'type' => Type::nonNull(Type::int())],
            'filters' => [
                'type' => \GraphQL::type('LifehackFilterInput'),
                'description' => 'Фильтры',
            ],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        if (null === \Auth::guard('json')->user()) {
            return null;
        }
        $query = $query = Lifehack::with($fields->getRelations())
            ->orderBy('sort', 'ASC')
            ->orderBy('created_at', 'DESC');

        if (false === empty($args['filters']['tag'])) {
            $tag = $args['filters']['tag'];
            $query->whereHas('tags', function ($q) use ($tag) {
                $q->where('tags.id', '=', $tag);
            });
        }
        $response = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

        return $response;
    }
}
