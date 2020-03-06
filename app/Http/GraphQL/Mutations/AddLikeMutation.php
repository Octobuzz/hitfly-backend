<?php

namespace App\Http\GraphQL\Mutations;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\Lifehack;
use App\Models\Like;
use GraphQL;
use InvalidArgumentException;
use Rebing\GraphQL\Support\Mutation;

class AddLikeMutation extends Mutation
{
    use GraphQLAuthTrait;

    protected $attributes = [
        'name'        => 'AddLike',
        'description' => 'Нравится',
    ];

    public function type()
    {
        return GraphQL::type('LikeResult');
    }

    public function args()
    {
        return [
            'Like' => [
                'type'  => GraphQL::type('LikeInput'),
                'rules' => 'like_unique_validate',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        switch ($args['Like']['likeableType']) {
            case Like::TYPE_LIFE_HACK:
                $class = Lifehack::class;
                break;
            default:
                throw new InvalidArgumentException('Не удалось определить тип лайка');
        }

        $like = Like::create([
            'likeable_type' => $class,
            'likeable_id'   => $args['Like']['likeableId'],
            'user_id'       => $this->getGuard()->user()->id,
        ]);
        $like->save();
        $entity = $like->belongsTo($class, 'likeable_id')->first();

        return $entity;
    }
}
