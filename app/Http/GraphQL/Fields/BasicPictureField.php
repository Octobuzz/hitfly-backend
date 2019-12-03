<?php

namespace App\Http\GraphQL\Fields;

use App\Helpers\PictureHelpers;
use App\Models\Album;
use App\Models\Collection;
use App\Models\MusicGroup;
use App\User;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Rebing\GraphQL\Support\Field;

class BasicPictureField extends Field
{
    protected $model;
    protected $factor;

    protected function setFactor($args)
    {
        if (!empty($args['factor'])) {
            $this->factor = $args['factor'];

            return;
        }

        if (!empty(config('image.factor'))) {
            $this->factor = config('image.factor');

            return;
        }

        $this->factor = 1;
    }

    /***
     *
     * @param $args
     *
     * @return array
     */
    protected function resolve($root, $args)
    {
        $this->setFactor($args);
        $this->model = $root;
        $class = get_class($root);

        switch ($class) {
            case Album::class:
                $defaultImage = 'default_album.svg';
                break;
            case Collection::class:
                $defaultImage = 'default_playlist.svg';
                break;
            case MusicGroup::class:
                $defaultImage = 'default_musicgroup.svg';
                break;
            case User::class:
                $defaultImage = 'default_account.png';
                break;
            default:
                $defaultImage = 'default_track.svg';
                break;
        }

        $keyCache = md5(serialize($root).serialize($args));
        $cacheValue = Cache::tags([$class.$root->id])->get($keyCache, null);
        if (null !== $cacheValue) {
            return $cacheValue;
        }

        $return = $this->getArrayResizedImage($args, $defaultImage);
        Cache::tags([$class.$root->id])->add($keyCache, $return, now()->addHour(6));

        return $return;
    }

    /**
     * @param int    $width
     * @param int    $height
     * @param string $picturePath
     * @param string $savePath
     * @param string $nameFile
     *
     * @return bool
     */
    protected function resize(int $width, int $height, string $picturePath, string $savePath, string $nameFile): bool
    {
        $width = (int) ($width * $this->factor);
        $height = (int) ($height * $this->factor);
        $image_resize = Image::make(Storage::disk('public')->path($picturePath))
            ->fit($width, $height);
        Storage::disk('public')->makeDirectory($this->model->getPath());

        $image_resize->save($savePath.$nameFile, 100);

        return true;
    }

    /**
     * @param string $picturePath
     * @param $size
     * @param $path
     *
     * @return mixed
     */
    protected function resizeImage(string $picturePath, $size, $path)
    {
        $image = new File(Storage::disk('public')->path($picturePath));

        $baseNameFile = pathinfo($image, PATHINFO_FILENAME);
        $saveName = "${baseNameFile}_${size}_f{$this->factor}_.".$image->getExtension();

        $savePicturePath = $this->model->getPath().$saveName;
        $url = Storage::disk('public')->url($savePicturePath);

        if (false === Storage::disk('public')->exists($savePicturePath)) {
            list($width, $height) = $this->model->getSizePicture($size);
            $this->resize($width, $height, $picturePath, $path, $saveName);
        }

        return $url;
    }

    /**
     * @param $args
     * @param string $defaultImage
     *
     * @return array
     */
    protected function getArrayResizedImage($args, string $defaultImage): array
    {
        $model = $this->model::find($this->model->id);
        if (null === $model) {
            $picturePath = $defaultImage;
        } else {
            $picturePath = $model->getImage();
        }
        if (false === PictureHelpers::isBitmapImage($picturePath)) {
            $picturePath = $defaultImage;
        }
        $path = Storage::disk('public')->path($this->model->getPath());

        $return = [];

        foreach ($args['sizes'] as $size) {
            if (false === PictureHelpers::isBitmapImage($picturePath)) {//$defaultImage может оказаться не растровым изображением
                $url = Storage::disk('public')->url($defaultImage);
            } else {
                $url = $this->resizeImage($picturePath, $size, $path);
            }

            $return[] = [
                'size' => $size,
                'url' => $url,
            ];
        }

        return $return;
    }
}
