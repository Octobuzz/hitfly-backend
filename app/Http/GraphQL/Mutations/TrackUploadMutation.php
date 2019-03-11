<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\Track;
use GraphQL;
use Rebing\GraphQL\Support\UploadType;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TrackUploadMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UpdateTrack',
    ];

    public function type()
    {
        return GraphQL::type('Track');
    }

    public function args()
    {
        return [
            'track' => [
                'name' => 'track',
                'type' => UploadType::getInstance(),
                'rules' => ['required'],
            ],
            'infoTrack' => [
                'type' => \GraphQL::type('TrackInput'),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        /** @var UploadedFile $file */
        $file = $args['track'];

        $nameFile = md5(microtime());
        $fullName = $nameFile.'.'.$file->getClientOriginalExtension();

        Storage::putFileAs('public/music', $file, $fullName);

        $track = Track::query()->create([
            'track_name' => empty($args['infoTrack']['trackName']) ? null : $args['infoTrack']['trackName'],
            'album_id' => empty($args['infoTrack']['album']) ? null : $args['infoTrack']['album'],
            'genre_id' => $args['infoTrack']['genre'],
            'singer' => $args['infoTrack']['singer'],
            'song_text' => empty($args['infoTrack']['songText']) ? null : $args['infoTrack']['songText'],
            'track_date' => empty($args['infoTrack']['trackDate']) ? null : $args['infoTrack']['trackDate'],
            'track_hash' => hash_file('md5', $file),
            'state' => 'upload',
            'filename' => $fullName,
        ]);
        ;
        echo(get_class($track));
        print_r($track);
        die();
        return response()->json($track);
    }
}
