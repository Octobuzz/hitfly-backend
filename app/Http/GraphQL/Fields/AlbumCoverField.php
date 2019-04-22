<?php
/**
 * Created by PhpStorm.
 * User: Dmitrii
 * Date: 17.04.2019
 * Time: 15:51
 */

namespace App\Http\GraphQL\Fields;


use App\Models\Album;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Rebing\GraphQL\Support\Field;

class AlbumCoverField extends Field
{
    protected $attributes = [
        'description'   => 'Абложка альбома',
        'selectable'   => false,
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
                'type' => Type::listOf(\GraphQL::type('AlbumSizeEnum')),
                'description' => 'Размеры изображений'
            ],

        ];
    }

    /***
     * @param Album $album
     * @param $args
     * @return array
     */
    protected function resolve($album, $args)
    {
        $return = [];
        foreach ($args['sizes'] as $size) {
            $this->path = $this->getPath($size,$album->user_id, $album->cover);
            $returnPath = $this->path['imagePath'] . $this->path['imageName'];
            if (!file_exists($this->path['public'].$this->path['imagePath'].$this->path['imageName'])) {
                if($album->getOriginal('cover')=== null) {
                    $returnPath = $this->resizeAlbum($size, false, true);
                }
                else {
                    $returnPath = $this->resizeAlbum($size, $album->cover);
                }
            }

            $return[] = [
                "size"=> $size,
                "url" => Storage::url($returnPath)
            ];
        }
        return $return;
    }

    /**
     * @param $size
     * @param $image
     * @param bool $default
     * @return string
     */
    protected function resizeAlbum($size, $image, $default = false){
        if($image===false){
            $image = Storage::disk('local')->getAdapter()->getPathPrefix();
            $image.= "default_image/album.png";
            $this->path['imageName'] = "default.png";
        }
        $image_resize = Image::make($image)
            ->resize(config('image.size.album.' . $size . '.width'), config('image.size.album.' . $size . '.height'), function ($constraint) {
                $constraint->aspectRatio();
            });

        //создадим папку, если несуществует
        if($default) $this->path['imagePath'] = "albums/";//сохраним размеры умолчанию в корневую папку
        if (!file_exists($this->path['public'] . $this->path['imagePath'])) {
            Storage::disk('public')->makeDirectory($this->path['imagePath']);
        }
        $image_resize->save($this->path['public'] . $this->path['imagePath'] . $this->path['imageName']);
        return $this->path['imagePath'] . $this->path['imageName'];
    }

    /**
     * получим части пути картинки
     * @param $size
     * @param $userId
     * @param $image
     * @return array
     */
    protected function getPath($size,$userId, $image){
        $publicPath = Storage::disk('public')->getAdapter()->getPathPrefix();
        $imageUrl = parse_url($image, PHP_URL_PATH);
        $extension = pathinfo($imageUrl, PATHINFO_EXTENSION);
        $avatarFileName  = pathinfo($imageUrl, PATHINFO_FILENAME);
        $path = "albums/$userId/";
        $imageName = "{$avatarFileName}_{$size}.{$extension}";
        return [
            'public' =>$publicPath,
            'imagePath' =>$path,
            'imageName' =>$imageName,
        ];
    }

}