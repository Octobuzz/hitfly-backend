<?php

namespace App\Http\GraphQL\Query;

use App\Helpers\DBHelpers;
use App\Models\Collection;
use App\Models\Track;
use App\User;
use GraphQL\Type\Definition\Type;
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
        $query = Track::with($fields->getRelations());

        $query->select('tracks.*');

        if (false === empty($args['filters']['my']) && true === $args['filters']['my'] && null !== \Auth::user()) {
            $query->where('tracks.user_id', '=', \Auth::user()->id);
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

//            $query->leftJoin('collections', function ($join) {
//                $join->on('collection_track.collection_id', '=', 'collections.id');
//            });
//            $query->where('collections.is_admin', '=', 0);
            $query->groupBy('tracks.id');
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
            $user = Auth::user();
            if (
                false === empty($args['filters']['iCommented'])
                && true === (bool) $args['filters']['iCommented']
            ) {
                $query->where('comments.user_id', '=', $user->id);
            }
            if (
                false === empty($args['filters']['commentedByUser'])
            ) {
                $query->where('comments.user_id', '=', $args['filters']['commentedByUser']);
            }
        }

        $response = $query->paginate($args['limit'], ['*'], 'page', $args['page']);
        return $response;
    }
}
