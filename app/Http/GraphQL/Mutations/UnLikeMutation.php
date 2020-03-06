<?php

namespace App\Http\GraphQL\Mutations;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\Lifehack;
use App\Models\Like;
use Exception;
use GraphQL;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use InvalidArgumentException;
use Rebing\GraphQL\Support\Mutation;

class UnLikeMutation extends Mutation
{
    use GraphQLAuthTrait;

    protected $attributes = [
        'name'        => 'UnLikeMutation',
        'description' => 'Отменить лайк',
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
                'rules' => 'like_delete_validate',
            ],
        ];
    }

    /**
     * @param mixed $root
     * @param array $args
     * @return Model|BelongsTo|object|null
     * @throws Exception
     */
    public function resolve($root, $args)
    {
        switch ($args['Like']['likeableType']) {
            case Like::TYPE_LIFE_HACK:
                $class = Lifehack::class;
                break;
            default:
                throw new InvalidArgumentException('Не удалось определить тип лайка');
        }

        $user = $this->getGuard()->user();

        $like = Like::query()
            ->where('likeable_id', $args['Favourite']['likeableId'])
            ->where('likeable_type', $class)
            ->where('user_id', $user->id)
            ->first();
        if (null === $like) {
            return null;
        }
        $entity = $like->belongsTo($class, 'likeable_id')->first();
        $like->delete();

        return $entity;
    }
}
