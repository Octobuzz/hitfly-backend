<?php

namespace App\Http\GraphQL\Mutations;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\Lifehack;
use App\Models\Like;
use GraphQL;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    /**
     * @param mixed $root
     * @param array $args
     * @return Model|BelongsTo|object|null
     * @throws InvalidArgumentException
     */
    public function resolve($root, $args)
    {
        /** @var Lifehack $class */
        $class = Like::getTypeClass($args['Like']['likeableType']);

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
