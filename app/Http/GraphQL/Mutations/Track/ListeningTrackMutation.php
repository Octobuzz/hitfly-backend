<?php

namespace App\Http\GraphQL\Mutations\Track;

use App\Events\ListeningTenTrackEvent;
use App\Events\Track\TrackMinimumListening;
use App\Models\Track;
use App\User;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Rebing\GraphQL\Support\Mutation;

class ListeningTrackMutation extends Mutation
{
    protected $attributes = [
        'name' => 'ListeningTrackMutation',
        'description' => 'Прослушивание трека',
    ];

    public function type()
    {
        return \GraphQL::type('Track');
    }

    public function args()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Индетификатор трека',
            ],
            'listening' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Количество прослушаного трека в % . Максимально 100',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        if ($args['listening'] < Track::MIN_LISTENING) {
            return;
        }

        $date = Carbon::now();
        $dateTomorrow = Carbon::now()->addDays(1)->setTime(0, 0);

        $minutes = $date->diffInMinutes($dateTomorrow);

        /** @var User $user */
        $user = \Auth::user();
        $keyUser = md5($date->format('Y-m-d').'_'.$user->id);
        $keyTracks = md5($date->format('Y-m-d').'_'.$user->id.'_tracks');

        $idTrack = $args['id'];
        $track = Track::query()->find($idTrack);
        if (null === $track) {
            return;
        }

        event(new TrackMinimumListening($track, Auth::user()));

        $cacheTracks = Cache::get($keyTracks, null);
        if (null !== $cacheTracks) {
            if (false !== array_search($idTrack, $cacheTracks, true)) {
                return;
            }
        }

        $cacheTracks[] = $idTrack;
        Cache::put($keyTracks, $cacheTracks, $minutes);

        /** @var int $cacheUser */
        $cacheUser = Cache::get($keyUser, null);
        if (null !== $cacheUser) {
            $cacheUser = ($cacheUser > 0) ? $cacheUser++ : 1;
            Cache::put($keyUser, $cacheUser, $minutes);
        }

        if ($cacheUser < 10 || $cacheUser > 10) {
            return;
        }

        event(new ListeningTenTrackEvent($user));
    }
}
