<?php

namespace App\Http\GraphQL\Mutations\Track;

use App\Models\Track;
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
        $track = Track::query()->find($args['id']);

        $track->update([
            'track_name' => empty($args['infoTrack']['trackName']) ? null : $args['infoTrack']['trackName'],
            'album_id' => empty($args['infoTrack']['album']) ? null : $args['infoTrack']['album'],
            'genre_id' => $args['infoTrack']['genre'],
            'singer' => $args['infoTrack']['singer'],
            'song_text' => empty($args['infoTrack']['songText']) ? null : $args['infoTrack']['songText'],
            'track_date' => empty($args['infoTrack']['trackDate']) ? null : $args['infoTrack']['trackDate'],
            'state' => 'fileload',
        ]);
        $track->save();

        return $track;
    }
}
