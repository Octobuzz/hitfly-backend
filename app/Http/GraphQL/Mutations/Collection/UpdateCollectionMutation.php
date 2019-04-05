<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 05.03.19
 * Time: 15:17.
 */

namespace App\Http\GraphQL\Mutations\Collection;

use App\Models\Collection;
use GraphQL\Type\Definition\Type;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\UploadType;

class UpdateCollectionMutation extends Mutation
{
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
                'rules' => ['required', 'image', 'max:1500'],
            ],
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Наззвание коллекции',
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'Наззвание коллекции',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $user = \Auth::guard('json')->user();

        if (null === $user) {
            return JsonResponse::create();
        }

        $collection = Collection::query()->find($args['id']);

        if (false === empty($args['image'])) {
            /* @var UploadedFile $file */
            $file = $args['image'];
            $fileName = md5(microtime()).'.'.$file->getClientOriginalExtension();

            Storage::putFileAs('public/collection/'.$user->id, $file, $fileName);

            $collection->image = $fileName;
            $collection->save();
        }

    }
}
