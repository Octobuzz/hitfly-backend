<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 05.03.19
 * Time: 15:17.
 */

namespace App\Http\GraphQL\Mutations\Collection;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\Collection;
use App\Rules\OwnerCollection;
use GraphQL\Type\Definition\Type;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\UploadType;
use Intervention\Image\Facades\Image;

class UpdateCollectionMutation extends Mutation
{
    use GraphQLAuthTrait;
    protected $attributes = [
        'name' => 'UpdateCollection',
        'description' => 'Обновление коллекции поользователя',
    ];

    public function type()
    {
        return \GraphQL::type('Collection');
    }

    public function args()
    {
        return [
            'image' => [
                'name' => 'image',
                'type' => UploadType::getInstance(),
                'rules' => ['image', 'max:1500'],
            ],
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Наззвание коллекции',
                'rules' => ['required', new OwnerCollection()],
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'Наззвание коллекции',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $user = $this->getGuard()->user();

        if (null === $user) {
            return JsonResponse::create();
        }

        $collection = Collection::query()->find($args['id']);
        if (null === $collection) {
            return null;
        }
        if (false === empty($args['image'])) {
            /* @var UploadedFile $file */

            $collection->image = $this->setCover($collection, $args['image']);
            $collection->save();
        }
        if (false === empty($args['name'])) {
            $collection->title = $args['name'];
            $collection->save();
        }

        return $collection;
    }

    /**
     * добавление аватарки группы.
     *
     * @param $collect
     * @param $avatar
     *
     * @return string
     */
    private function setCover($collect, $avatar)
    {
        if (null !== $collect->getOriginal('image')) {
            Storage::disk('public')->delete($collect->getOriginal('image'));
        }
        $image = $avatar;
        $nameFile = md5(microtime());
        $imagePath = "collections/$collect->user_id/".$nameFile.'.'.$image->getClientOriginalExtension();
        $image_resize = Image::make($image->getRealPath());
        $image_resize->fit(config('image.size.collection.default.height'), config('image.size.collection.default.height')/*, function ($constraint) {
            $constraint->aspectRatio();
        }*/);
        $path = Storage::disk('public')->getAdapter()->getPathPrefix();
        //создадим папку, если несуществует
        if (!file_exists($path.'collections/'.$collect->user_id)) {
            Storage::disk('public')->makeDirectory('collections/'.$collect->user_id);
        }
        $image_resize->save($path.$imagePath, 100);

        return $imagePath;
    }
}
