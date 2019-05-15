<?php

namespace App\Http\GraphQL\Mutations\Track;

use App\Models\Track;
use Carbon\Carbon;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class UpdateTrackMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UpdateTrack',
        'description' => 'Обновление информации о треке',
    ];

    public function type()
    {
        return GraphQL::type('Track');
    }

    public function args()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Индетификатор трека',
            ],
            'infoTrack' => [
                'type' => \GraphQL::type('TrackInput'),
                'description' => 'Информация о треке',
            ],
        ];
    }

    public function rules(array $args = [])
    {
        return [
            'id' => ['required', function ($attribute, $value, $fail) {
                $user = \Auth::user();
                $track = Track::query()->find($value);
                if (true === empty($track)) {
                    $fail('Трек  не найден');
                }
                if ($track->user_id !== $user->id) {
                    $fail('Не достаточно прав на изменение информации треке');
                }
            }],
        ];
    }

    public function resolve($root, $args)
    {
        /** @var Track $track */
        $track = Track::query()->find($args['id']);
        $track->genres()->sync($args['infoTrack']['genres']);

        $track->update([
            'track_name' => $args['infoTrack']['trackName'],
            'album_id' => empty($args['infoTrack']['album']) ? null : $args['infoTrack']['album'],
            'singer' => $args['infoTrack']['singer'],
            'song_text' => $args['infoTrack']['songText'],
            'track_date' => Carbon::create($args['infoTrack']['trackDate'], 1, 1),
            'state' => 'fileload',
        ]);

        $track->save();

        return $track;
    }
}
