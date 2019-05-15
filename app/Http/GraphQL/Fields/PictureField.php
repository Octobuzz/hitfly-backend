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
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Rebing\GraphQL\Support\Field;

class PictureField extends Field
{
    /**
     * @var Album
     */
    private $model;
    protected $attributes = [
        'description' => 'Получение изображений',
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
                'type' => Type::nonNull(Type::listOf(\GraphQL::type('PictureSizeEnum'))),
                'description' => 'Размеры изображений',
            ],
        ];
    }

    /***
     *
     * @param $args
     *
     * @return array
     */
    protected function resolve($root, $args)
    {
        $this->model = $root;

        $return = [];

        $defaultImage = 'default.jpg';
        $picturePath = $this->model->getImage(); //todo
        $path = Storage::disk('public')->path($this->model->getPath());

        foreach ($args['sizes'] as $size) {
            if (false === Storage::disk('public')->exists($picturePath)) {
                $picturePath = $defaultImage;
            }

            $image = new File(Storage::disk('public')->path($picturePath));

            $baseNameFile = pathinfo($image, PATHINFO_FILENAME);
            $saveName = "${baseNameFile}_${size}.".$image->getExtension();

            $savePicturePath = $this->model->getPath().$saveName;
            $url = Storage::disk('public')->url($savePicturePath);

            if (false === Storage::disk('public')->exists($savePicturePath)) {
                list($width, $height) = $this->model->getSizePicture($size);
                $this->resize($width, $height, $picturePath, $path, $saveName);
            }

            $return[] = [
                'size' => $size,
                'url' => $url,
            ];
        }

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
    private function resize(int $width, int $height, string $picturePath, string $savePath, string $nameFile): bool
    {
        $image_resize = Image::make(Storage::disk('public')->path($picturePath))
            ->fit($width, $height);
        Storage::disk('public')->makeDirectory($this->model->getPath());

        $image_resize->save($savePath.$nameFile, 100);

        return true;
    }
}
