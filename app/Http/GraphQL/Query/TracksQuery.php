<?php

namespace App\Http\GraphQL\Query;

use App\Helpers\DBHelpers;
use App\Models\Track;
use App\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

/**
 * Class AlbumQuery.
 */
class TracksQuery extends Query
{
    protected $attributes = [
        'name' => 'Music Group Query',
        'description' => 'Запрос треков',
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
            'my' => [
                'name' => 'my',
                'type' => Type::boolean(),
                'description' => 'Только мои треки',
                'rules' => ['mutually_exclusive_args:userId,musicGroupId'],
            ],
            'commentPeriod' => [
                'type' => \GraphQL::type('CommentPeriodEnum'),
                'description' => 'Фильтрация треков по комментариям (треки которые были прокомментированы)',
            ],
            'userId' => [
                'type' => Type::int(),
                'description' => 'ID пользователя(фильтрация)',
                'rules' => ['mutually_exclusive_args:my,musicGroupId'],
            ],
            'musicGroupId' => [
                'type' => Type::int(),
                'description' => 'ID группы(фильтрация)',
                'rules' => ['mutually_exclusive_args:my,userId'],
            ],
            'iCommented' => [
                'type' => Type::boolean(),
                'description' => 'Треки откоментированные мною (для звезды, не обязательно). Работает совместно с commentPeriod',
            ],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        $query = Track::with($fields->getRelations());

        $query->select('tracks.*');

        if (false === empty($args['my']) && true === $args['my'] && null !== \Auth::user()) {
            $query->where('tracks.user_id', '=', \Auth::user()->id);
        }
        if (false === empty($args['userId'])) {
            $query->where('tracks.user_id', '=', $args['userId']);
        }
        if (false === empty($args['musicGroupId'])) {
            $query->where('tracks.music_group_id', '=', $args['musicGroupId']);
        }

        if (false === empty($args['commentPeriod'])) {
            $date = DBHelpers::getPeriod($args['commentPeriod']);
            $query->rightJoin('comments', function ($join) {
                $join->on('tracks.id', '=', 'comments.commentable_id');
            })
                ->where('comments.created_at', '>=', $date)
                ->where('comments.commentable_type', '=', Track::class)
                ->groupBy('tracks.id');

            /// Треки откоментированные мною
            /** @var User $user */
            $user = \Auth::user();
            if (
                true === (bool) $args['iCommented']
                && true === $user->roles->has('star')
            ) {
                $query->where('comments.user_id', '=', $user->id);
            }

        }

        $response = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

        return $response;
    }
}
