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
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class CollectionsQuery extends Query
{
    protected $attributes = [
        'name' => 'Collections Query',
        'description' => 'Коллекции(Playlist)',
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
            'filters' => [
                'type' => \GraphQL::type('CollectionFilterInput'),
                'description' => 'Фильтры',
            ],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        $query = Collection::with($fields->getRelations());
        if (false === empty($args['filters']['my']) && true === $args['filters']['my'] && null !== \Auth::user()) {
            $query->where('user_id', '=', Auth::user()->id);
        }
        if (false === empty($args['filters']['userId'])) {
            $query->where('user_id', '=', $args['filters']['userId']);
        }

        // Запрос подборок
        if (false === empty($args['filters']['collection']) && true === (bool) $args['filters']['collection']) {
            $query->where('is_admin', '=', (bool) $args['filters']['collection']);
        }

        // Запрос супер меломан
        if (false === empty($args['filters']['superMusicFan']) && true === (bool) $args['filters']['superMusicFan']) {
            $query->where('super_music_fan', '=', (bool) $args['filters']['superMusicFan']);
        }
        $response = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

        return $response;
    }
}
