<?php
/**
 * Created by PhpStorm.
 * User: Dmitrii
 * Date: 17.04.2019
 * Time: 15:51.
 */

namespace App\Http\GraphQL\Fields;

use App\Models\Album;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Rebing\GraphQL\Support\Field;

class MusicGroupImageField extends Field
{
    protected $attributes = [
        'description' => 'Аватар группы',
        'selectable' => false,
    ];

    protected $path;

    public function type()
    {
        return Type::listOf(\GraphQL::type('ImageSizesType'));
    }

    public function args()
    {
        return [
            'sizes' => [
                'type' => Type::nonNull(Type::listOf(\GraphQL::type('MusicGroupSizeEnum'))),
                'description' => 'Размеры изображений',
            ],
        ];
    }

    /***
     * @param Album $collect
     * @param $args
     * @return array
     */
    protected function resolve($root, $args)
    {
        $return = [];
        foreach ($args['sizes'] as $size) {
            $this->path = $this->getPath($size, $root->creator_group_id, $root);
            $returnPath = $this->path['imagePath'].$this->path['imageName'];
            if (!file_exists($this->path['public'].$this->path['imagePath'].$this->path['imageName'])) {
                if (null === $root->getOriginal('avatar_group')) {
                    $returnPath = $this->resizeAlbum($size, false, true);
                } else {
                    $returnPath = $this->resizeAlbum($size, $root->getOriginal('avatar_group'), 100);
                }
            }

            $return[] = [
                'size' => $size,
                'url' => Storage::disk('public')->url($returnPath),
            ];
        }

        return $return;
    }

    /**
     * @param $size
     * @param $image
     * @param bool $default
     *
     * @return string
     */
    protected function resizeAlbum($size, $image, $default = false)
    {
        if (false === $image) {
            $image = Storage::disk('local')->getAdapter()->getPathPrefix();
            $image .= 'default_image/music_group.png';
            $this->path['imageName'] = 'default_'.$size.'.png';
        } else {//путь к картинке вместо урл
            $image = $this->path['public'].$image;
        }
        $image_resize = Image::make($image)
            ->fit(config('image.size.music_group.'.$size.'.width'), config('image.size.music_group.'.$size.'.height')/*, function ($constraint) {
                $constraint->aspectRatio();
            }*/);

        //создадим папку, если несуществует
        if ($default) {
            $this->path['imagePath'] = 'music_groups/';
        } //сохраним размеры умолчанию в корневую папку
        if (!file_exists($this->path['public'].$this->path['imagePath'])) {
            Storage::disk('public')->makeDirectory($this->path['imagePath']);
        }
        $image_resize->save($this->path['public'].$this->path['imagePath'].$this->path['imageName'], 100);

        return $this->path['imagePath'].$this->path['imageName'];
    }

    /**
     * получим части пути картинки.
     *
     * @param $size
     * @param $userId
     * @param $image
     *
     * @return array
     */
    protected function getPath($size, $userId, $image)
    {
        $publicPath = Storage::disk('public')->getAdapter()->getPathPrefix();
        $imagePath = $publicPath.$image->getOriginal('avatar_group');
        $extension = pathinfo($imagePath, PATHINFO_EXTENSION);
        $avatarFileName = pathinfo($imagePath, PATHINFO_FILENAME);
        $path = "music_groups/$userId/";
        $imageName = "{$avatarFileName}_{$size}.{$extension}";

        return [
            'public' => $publicPath,
            'imagePath' => $path,
            'imageName' => $imageName,
        ];
    }
}
