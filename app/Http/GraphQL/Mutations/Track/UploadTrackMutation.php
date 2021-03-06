<?php

namespace App\Http\GraphQL\Mutations\Track;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\Track;
use GraphQL;
use Rebing\GraphQL\Support\UploadType;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadTrackMutation extends Mutation
{
    use GraphQLAuthTrait;
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
                'rules' => ['required', 'mimetypes:audio/ogg,audio/wave,audio/x-wav,audio/x-pn-wav,audio/aac,audio/mp4,audio/vnd.wave,audio/flac,audio/x-flac,audio/vnd.wave,audio/x-aiff,audio/aiff,audio/x-m4a,audio/mp3,audio/mpeg'],
            ],
        ];
    }

    public function resolve($root, $args)
    {
        /** @var UploadedFile $file */
        $file = $args['track'];
        $user = $this->getGuard()->user();
        $name = Storage::disk('public')->putFile("tracks/$user->id", $file);

        $track = Track::query()->create([
            'track_date' => new \DateTime(),
            'track_hash' => hash_file('md5', $file),
            'user_id' => $user->id,
            'filename' => $name,
        ]);

        return $track;
    }
}
