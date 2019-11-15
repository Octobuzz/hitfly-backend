<?php

namespace App\Http\GraphQL\Mutations\Track;

use App\Events\ListeningTenTrackEvent;
use App\Events\Track\TrackMinimumListening;
use App\Models\ListenedTrack;
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

    public function authorize(array $args)
    {
        return Auth::check();
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
        $user = Auth::user();
        $keyUser = md5($date->format('Y-m-d').'_'.$user->id);
        $keyTracks = md5($date->format('Y-m-d').'_'.$user->id.'_tracks');

        $idTrack = $args['id'];
        $track = Track::query()->find($idTrack);
        if (null === $track) {
            return;
        }
        //проверка на прослушивание одного трека только один раз, иначе бонусы неположены
        //получить данные надо перед вызовом события TrackMinimumListening, пока в бд не записалось текущее прослушивание
        $listenedTrack = ListenedTrack::query()->select('id')->where('user_id', $user->id)
            ->where('track_id', $track->id)->first();
        event(new TrackMinimumListening($track, $user));

        //если уже прослушивал этот трек, не засчитываем прослушивание
        if (null !== $listenedTrack) {
            return;
        }

        $cacheTracks = Cache::get($keyTracks, null);
        if (null !== $cacheTracks) {
            if (false !== array_search($idTrack, $cacheTracks, true)) {
                return;
            }
        }

        $cacheTracks[] = $idTrack;
        Cache::put($keyTracks, $cacheTracks, $minutes);

        /** @var int $cacheUser */
        $cacheUser = (int) Cache::get($keyUser, 0);
        ++$cacheUser;
        Cache::put($keyUser, $cacheUser, $minutes);

        if ($cacheUser < 10 || $cacheUser > 10) {
            return;
        }

        event(new ListeningTenTrackEvent($user));
    }
}
