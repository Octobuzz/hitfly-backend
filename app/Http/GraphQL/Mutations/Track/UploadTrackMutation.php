<?php

namespace App\Http\GraphQL\Mutations\Track;

use App\Models\Track;
use GraphQL;
use Rebing\GraphQL\Support\UploadType;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadTrackMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UploadTrack',
        'description' => 'Загрузка трека',
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
        ];
    }

    public function resolve($root, $args)
    {
        /** @var UploadedFile $file */
        $file = $args['track'];
        $user = \Auth::user();
        $name = Storage::disk('public')->putFile("tracks/$user->id", $file);

        $track = Track::query()->create([
            'track_name' => 'newTrack',
            'singer' => '',
            'song_text' => '',
            'track_date' => new \DateTime(),
            'track_hash' => hash_file('md5', $file),
            'state' => 'fileload',
            'user_id' => $user->id,
            'filename' => $name,
        ]);

        return $track;
    }
}
