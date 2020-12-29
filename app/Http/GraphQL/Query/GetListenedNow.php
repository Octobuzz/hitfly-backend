<?php

namespace App\Http\GraphQL\Query;

use App\BuisnessLogic\TopFifty;
use App\Models\Track;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Cache;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class GetListenedNow extends Query
{
    protected $attributes = [
        'name' => 'GetListenedNow',
        'description' => 'Получение списка "Слушают сейчас"',
    ];

    public function type()
    {
        return \GraphQL::paginate('Track');
    }

    public function args()
    {
        return [
            'limit' => ['name' => 'limit', 'type' => Type::int()],
            'page' => ['name' => 'page', 'type' => Type::int()],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        $idsTracks = Cache::get(TopFifty::LISTENED_TRACK_KEY_CALCULATED, null);
        if (null === $idsTracks) {
            return null;
        }
        $query = Track::with($fields->getRelations());

        $query->whereIn('id', $idsTracks);

        $response = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

        return $response;
    }
}
