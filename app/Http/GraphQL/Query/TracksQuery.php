<?php

namespace App\Http\GraphQL\Query;

use App\Helpers\DBHelpers;
use App\Models\Track;
use App\User;
use GraphQL\Type\Definition\Type;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
            'commentPeriod' => [
                'type' => \GraphQL::type('CommentPeriodEnum'),
                'description' => 'Фильтрация треков по комментариям (треки которые были прокомментированы)',
            ],
            'filters' => [
                'type' => \GraphQL::type('TrackFilterInput'),
                'description' => 'Фильтры',
            ],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        $query = Track::with($fields->getRelations())
            ->orderBy('created_at', 'DESC');

        $query->select('tracks.*');

        if (false === empty($args['filters']['my']) && true === $args['filters']['my']) {
            if (null === Auth::guard('json')->user()) {
                return null;
            }
            $query->where('tracks.user_id', '=', Auth::guard('json')->user()->id);
        }
        if (false === empty($args['filters']['userId'])) {
            $query->where('tracks.user_id', '=', $args['filters']['userId']);
        }
        if (false === empty($args['filters']['musicGroupId'])) {
            $query->where('tracks.music_group_id', '=', $args['filters']['musicGroupId']);
        }
        if (false === empty($args['filters']['albumId'])) {
            $query->where('tracks.album_id', '=', $args['filters']['albumId']);
        }
        if (false === empty($args['filters']['playlistId']) || false === empty($args['filters']['collectionId'])) {
            if (false === empty($args['filters']['playlistId'])) {
                $filterId = $args['filters']['playlistId'];
            } else {
                $filterId = $args['filters']['collectionId'];
            }
            $query->rightJoin('collection_track', function ($join) {
                $join->on('collection_track.track_id', '=', 'tracks.id');
            });
            $query->where('collection_track.collection_id', $filterId);

            $query->groupBy('tracks.id');
        }

        if (false === empty($args['commentPeriod'])) {
            $date = DBHelpers::getPeriod($args['commentPeriod']);

            $query->leftJoin('comments', function ($join) {
                $join->on('tracks.id', '=', 'comments.commentable_id');
            })
                ->where('comments.created_at', '>=', $date)
                ->where('comments.commentable_type', '=', Track::class)
                ->orderBy('tracks.id', 'DESC')
                ->groupBy('tracks.id');

            /// Треки откоментированные мною
            /** @var User $user */
            $user = Auth::guard('json')->user();
            if (
                false === empty($args['filters']['iCommented'])
                && true === (bool) $args['filters']['iCommented']
            ) {
                if (null === $user) {
                    return null;
                }
                $query->where('comments.user_id', '=', $user->id);
            }
            if (
                false === empty($args['filters']['commentedByUser'])
            ) {
                $query->where('comments.user_id', '=', $args['filters']['commentedByUser']);
            }
        }

        if (false === empty($args['filters']['lastCommented'])) {
            $userQuery = User::query()->select('users.id');
            $userQuery->leftJoin('admin_role_users', 'users.id', '=', 'admin_role_users.user_id')
                ->leftJoin('admin_roles', 'admin_role_users.role_id', '=', 'admin_roles.id')
                ->where('admin_roles.slug', '=', 'star');
            $query->leftJoin('comments', function ($join) {
                $join->on('tracks.id', '=', 'comments.commentable_id');
            })
                ->whereIn('comments.user_id', $userQuery)
                ->where('comments.commentable_type', '=', Track::class)
                ->orderBy('tracks.id', 'DESC')
                ->groupBy('tracks.id');
        }

        if (false === empty($args['filters']['genre'])) {
            $query->leftJoin('genres_bindings', function ($join) {
                $join->on('tracks.id', '=', 'genres_bindings.genreable_id');
            })
                ->where('genres_bindings.genre_id', '=', $args['filters']['genre'])
                ->where('genres_bindings.genreable_type', '=', Track::class)
            ;
        }
        if (false === empty($args['filters']['new']) && true === $args['filters']['new']) {
            $query->where('tracks.created_at', '>=', Carbon::now()->subDays(14)->startOfDay());
        }

        $response = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

        return $response;
    }
}
