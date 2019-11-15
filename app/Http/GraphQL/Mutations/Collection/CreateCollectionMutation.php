<?php

namespace App\Http\GraphQL\Mutations\Collection;

use App\Models\Collection;
use App\Models\Track;
use GraphQL\Type\Definition\Type;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\UploadType;

class CreateCollectionMutation extends Mutation
{
    protected $attributes = [
        'name' => 'CreateCollectionMutation',
        'description' => 'Создание коллекции пользователя',
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
                'rules' => ['nullable', 'image', 'max:1500'],
                'description' => 'Изображение коллекции',
            ],
            'title' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Наззвание коллекции',
            ],
            'tracksId' => [
                'type' => Type::nonNull(Type::listOf(Type::int())),
                'description' => 'Индетификаторы треков',
            ],
        ];
    }

    public function authorize(array $args)
    {
        return Auth::guard('json')->check();
    }

    public function resolve($root, $args)
    {
        $user = Auth::guard('json')->user();

        if (null === $user) {
            return JsonResponse::create();
        }

        $collection = new Collection();
        $collection->title = $args['title'];
        $collection->user()->associate($user);
        $collection->save();

        if (false === empty($args['image'])) {
            /* @var UploadedFile $file */
            $fileName = $collection->fileProcessing((string) $args['image']);
            $collection->image = $fileName;
            $collection->save();
        }

        $tracks = Track::query()->whereIn('id', $args['tracksId'])->get();

        foreach ($tracks as $track) {
            $collection->tracks()->attach($track);
        }

        return $collection;
    }
}
