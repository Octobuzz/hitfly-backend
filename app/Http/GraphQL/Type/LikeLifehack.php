<?php

namespace App\Http\GraphQL\Type;

use App\Models\Favourite;
use App\Models\Like;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

class LikeLifehack extends \Rebing\GraphQL\Support\Type
{
    protected $attributes = [
        'name' => 'LikeLifehack',
        'model' => Like::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
            ],
            'lifehack' => [
                'type'        => GraphQL::type('LifehackType'),
                'description' => 'модель Lifehack',
            ],
            'userId' => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'ID пользователя',
                'alias'       => 'user_id',
            ],
            'createdAt' => [
                'type'        => Type::string(),
                'description' => 'дата создания',
                'alias'       => 'created_at',
                'resolve'     => function ($model) {
                    return $model->created_at;
                },
            ],
        ];
    }
}
