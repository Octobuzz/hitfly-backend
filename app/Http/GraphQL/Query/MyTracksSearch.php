<?php

namespace App\Http\GraphQL\Query;

use App\Models\Track;
use App\User;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Rebing\GraphQL\Support\Query;

/**
 * Class AlbumQuery.
 */
class MyTracksSearch extends Query
{
    protected $attributes = [
        'name' => 'MyTracksSearch',
        'description' => 'Поиск своих треков',
    ];

    public function type()
    {
        return Type::listOf(\GraphQL::type('Track'));
    }

    public function args()
    {
        return [
            'q' => [
                'name' => 'q',
                'description' => 'Поисковый запрос',
                'type' => Type::nonNull(Type::string()),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        if (isset($args['q'])) {
            /** @var User $user */
            $user = Auth::user();
            $keyCache = md5(json_encode($args).json_encode($user));

            $response = Cache::get($keyCache, null);

            if (false === empty($response)) {
                return $response;
            }
            $response = Track::query()->where('track_name', 'LIKE', '%'.$args['q'].'%')->where('user_id', '=', $user->id)->get();

            Cache::add($keyCache, $response, now()->addMinute(3));

            return $response;
        }

        return null;
    }
}
