<?php

namespace App\Http\GraphQL\Unions;

use App\Models\Album;
use App\Models\Collection;
use App\Models\Comment;
use App\Models\MusicGroup;
use App\Models\Track;
use App\User;
use Rebing\GraphQL\Support\UnionType;

class FollowUnion extends UnionType
{
    protected $attributes = [
        'name' => 'FollowResult',
    ];

    public function types()
    {
        return [
            \GraphQL::type('FollowUser'),
            \GraphQL::type('FollowMusicGroup'),

        ];
    }

    /**
     * @param Comment $value
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function resolveType($value)
    {
        switch ($value->watcheable_type) {
            case User::class:
                return \GraphQL::type('FollowUser');
                break;
            case MusicGroup::class:
                return \GraphQL::type('FollowMusicGroup');
                break;

            default:
                throw new \Exception('Немогу найти нужный тип подписки');
        }
    }
}
