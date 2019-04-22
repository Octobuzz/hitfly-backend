<?php
/**
 * Created by PhpStorm.
 * User: Dmitrii
 * Date: 17.04.2019
 * Time: 15:51.
 */

namespace App\Http\GraphQL\Fields;

use App\User;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Rebing\GraphQL\Support\Field;

class AvatarSizesField extends Field
{
    protected $attributes = [
        'description' => 'Аватар',
        'selectable' => false,
    ];

    public function type()
    {
        return Type::listOf(\GraphQL::type('ImageSizesType'));
    }

    public function args()
    {
        return [
            'sizes' => [
                'type' => Type::listOf(\GraphQL::type('AvatarSizeEnum')),
                'description' => 'Размеры изображений',
            ],
        ];
    }

    /***
     * @param User $root
     * @param $args
     * @return array
     */
    protected function resolve($root, $args)
    {
        $return = [];
        $returnPath = '';
        $user = Auth::user();
        foreach ($args['sizes'] as $size) {
            $publicPath = Storage::disk('public')->getAdapter()->getPathPrefix();
            $avatarUrl = parse_url($root->avatar, PHP_URL_PATH);
            $extension = pathinfo($avatarUrl, PATHINFO_EXTENSION);
            $avatarFileName = pathinfo($avatarUrl, PATHINFO_FILENAME);
            $path = "avatars/$user->id/";
            $imageName = "{$avatarFileName}_{$size}.{$extension}";
            if (!file_exists($publicPath.$path.$imageName)) {
                if (null === $root->getOriginal('avatar')) {
                    $returnPath = $this->resizeAvatar($size, $publicPath, $imageName, $root->avatar, $path, true);
                } else {
                    $returnPath = $this->resizeAvatar($size, $publicPath, $imageName, $root->avatar, $path);
                }
            }
            $return[] = [
                'size' => $size,
                'url' => Storage::url($returnPath),
            ];
        }

        return $return;
    }

    /**
     * @param $size
     * @param $publicPath
     * @param $imagePath
     * @param $avatar
     * @param $path
     * @param bool $default
     *
     * @return string
     */
    protected function resizeAvatar($size, $publicPath, $imagePath, $avatar, $path, $default = false)
    {
        $image_resize = Image::make($avatar)
            ->resize(config('image.size.avatar.'.$size.'.width'), config('image.size.avatar.'.$size.'.height'), function ($constraint) {
                $constraint->aspectRatio();
            });

        //создадим папку, если несуществует
        if ($default) {
            $path = 'avatars/';
        } //сохраним размеры авы по умолчанию в корневую папку
        if (!file_exists($publicPath.$path)) {
            Storage::disk('public')->makeDirectory($path);
        }
        $image_resize->save($publicPath.$path.$imagePath);

        return $path.$imagePath;
    }
}
