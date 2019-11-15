<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\Album;
use App\Rules\AuthorUpdateAlbum;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\UploadType;

class UpdateAlbumMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UpdateAlbumMutation',
        'description' => 'обновление альбома',
    ];

    public function type()
    {
        return \GraphQL::type('Album');
    }

    public function args()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'rules' => [new AuthorUpdateAlbum()],
            ],
            'album' => [
                'type' => \GraphQL::type('AlbumInput'),
            ],
            'cover' => [
                'description' => 'обложка альбома',
                'type' => UploadType::getInstance(),
            ],
        ];
    }

    public function authorize(array $args)
    {
        return Auth::check();
    }

    public function resolve($root, $args)
    {
        $album = Album::query()->find($args['id']);
        if (null === $album) {
            return null;
        }
        if (!empty($args['album']['type']) && null !== $args['album']['type']) {
            $album->type = $args['album']['type'];
        }
        if (!empty($args['album']['title']) && null !== $args['album']['title']) {
            $album->title = $args['album']['title'];
        }
        if (!empty($args['album']['author']) && null !== $args['album']['author']) {
            $album->author = $args['album']['author'];
        }

        if (!empty($args['album']['author']) && null !== $args['album']['author']) {
            $album->author = $args['album']['author'];
        }
        if (!empty($args['album']['year']) && null !== $args['album']['year']) {
            $album->year = Carbon::create($args['album']['year'], 1, 1);
        }

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
