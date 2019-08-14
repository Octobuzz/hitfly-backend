<?php

namespace App\Helpers;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class PictureHelpers
{
    public static function resizePicture($model, $width = 100, $height = 100): string
    {
        $size = $width.'_'.$height;
        $defaultImage = 'default.jpg';
        $picturePath = $model->getImage(); //todo
        $path = Storage::disk('public')->path($model->getPath());

        if (false === Storage::disk('public')->exists($picturePath)) {
            $picturePath = $defaultImage;
        }

        $image = new File(Storage::disk('public')->path($picturePath));

        $baseNameFile = pathinfo($image, PATHINFO_FILENAME);
        $saveName = "${baseNameFile}_${size}.".$image->getExtension();

        $savePicturePath = $model->getPath().$saveName;
        $url = Storage::disk('public')->url($savePicturePath);

        if (false === Storage::disk('public')->exists($savePicturePath)) {
            self::resize($width, $height, $picturePath, $path, $saveName);
        }

        return $url;
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
    private static function resize(int $width, int $height, string $picturePath, string $savePath, string $nameFile): bool
    {
        $image_resize = Image::make(Storage::disk('public')->path($picturePath))
            ->fit($width, $height);
        Storage::disk('public')->makeDirectory($savePath);

        $image_resize->save($savePath.$nameFile, 100);

        return true;
    }

    /**
     * растровое изображение?
     *
     * @param $image
     *
     * @return bool
     */
    public static function isBitmapImage($image): bool
    {
        if (false === Storage::disk('public')->exists($image)) {
            return false;
        }
        $imageFile = new File(Storage::disk('public')->path($image));
        $validator = Validator::make(
                ['image' => $imageFile],
                ['image' => 'mimes:jpeg,png,webp']
            );
        if (false === $validator->fails()) {
            return true;
        }

        return false;
    }
}
