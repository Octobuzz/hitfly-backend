<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\Album;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\UploadType;

class CreateAlbumMutation extends Mutation
{
    protected $attributes = [
        'name' => 'CreateAlbumMutation',
        'description' => 'Создание альбома',
    ];

    public function type()
    {
        return \GraphQL::type('Album');
    }

    public function args()
    {
        return [
            'album' => [
                'type' => \GraphQL::type('AlbumInput'),
            ],
            'cover' => [
                'description' => 'обложка альбома',
                'type' => UploadType::getInstance(),
//                'rules' => ['required'],
            ],
        ];
    }

    public function authorize(array $args)
    {
        return Auth::guard('json')->check();
    }

    public function resolve($root, $args)
    {
        /** @var User $user */
        $user = Auth::guard('json')->user();

        $album = new Album();
        $album->setUser($user);
        $album->type = $args['album']['type'];
        $album->title = $args['album']['title'];
        $album->author = $args['album']['author'];
        $album->music_group_id = empty($args['album']['musicGroup']) ? null : $args['album']['musicGroup'];
        $album->year = Carbon::createFromFormat('Y', $args['album']['year'])->toDateString();
        if (!empty($args['cover']) && null !== $args['cover']) {
            $album->cover = $this->setCover($album, $args['cover']);
        }

        $album->save();

        if (!empty($args['album']['genres'])) {
            $album->genres()->sync($args['album']['genres']);
        }

        return $album;
    }

    /**
     * добавление аватарки группы.
     *
     * @param $album
     * @param $avatar
     *
     * @return string
     */
    private function setCover($album, $avatar)
    {
        if (null !== $album->getOriginal('cover')) {
            Storage::disk('public')->delete($album->getOriginal('cover'));
        }
        $image = $avatar;
        $nameFile = md5(microtime());
        $imagePath = "albums/$album->user_id/".$nameFile.'.'.$image->getClientOriginalExtension();
        $image_resize = Image::make($image->getRealPath());
        $image_resize->fit(config('image.size.album.default.height'), config('image.size.album.default.height')/*, function ($constraint) {
            $constraint->aspectRatio();
        }*/);
        $path = Storage::disk('public')->getAdapter()->getPathPrefix();
        //создадим папку, если несуществует
        if (!file_exists($path.'albums/'.$album->user_id)) {
            Storage::disk('public')->makeDirectory('albums/'.$album->user_id);
        }
        $image_resize->save($path.$imagePath, 100);

        return $imagePath;
    }
}
