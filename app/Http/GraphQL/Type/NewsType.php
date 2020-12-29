<?php

namespace App\Http\GraphQL\Type;

use App\Models\News;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Storage;
use Rebing\GraphQL\Support\Type as GraphQLType;

class NewsType extends GraphQLType
{
    protected $attributes = [
        'name' => 'NewsType',
        'description' => 'Новость',
        'model' => News::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'ID Нововсти',
                'alias' => 'id', // Use 'alias', if the database column is different from the type name
            ],
            'title' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Навазние нововсти',
            ],
            'content' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'HTML тект новости',
            ],
            'createAt' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Дата создания',
                'alias' => 'created_at',
            ],
            'image' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'URL изображения нововсти',
                'resolve' => function ($model) {
                    if (null !== $model->image) {
                        return Storage::disk('public')->url($model->image);
                    }
                },
            ],
        ];
    }
}
