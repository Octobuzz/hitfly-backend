<?php

namespace App\Validation;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\Lifehack;
use App\Models\Like;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class LikeValidator extends Validator
{
    use GraphQLAuthTrait;

    /**
     * @param string $attr
     * @param mixed $value
     * @param mixed $params
     * @param \Illuminate\Validation\Validator $validator
     * @throws InvalidArgumentException
     * @return bool
     */
    public function unique(string  $attr, $value, $params, \Illuminate\Validation\Validator $validator): bool
    {
        $data = $validator->getData();

        /** @var Lifehack $class */
        $class = Like::getTypeClass($data['Like']['likeableType']);
        $class::findOrFail($data['Like']['likeableId']);

        $like = Like::query()
            ->where('likeable_type', '=', $class)
            ->where('likeable_id', '=', $data['Like']['likeableId'])
            ->where('user_id', $this->getGuard()->user()->id)
            ->first();
        return null === $like;
    }

    /**
     * @param string $attr
     * @param mixed $value
     * @param mixed $params
     * @param \Illuminate\Validation\Validator $validator
     * @return bool
     */
    public function delete(string  $attr, $value, $params, \Illuminate\Validation\Validator $validator): bool
    {
        $data = $validator->getData();

        /** @var Lifehack $class */
        $class = Like::getTypeClass($data['Like']['likeableType']);

        $like = Like::query()
            ->where('likeable_type', '=', $class)
            ->where('likeable_id', '=', $data['Like']['likeableId'])
            ->where('user_id', $this->getGuard()->user()->id)
            ->first();

        return null !== $like;
    }
}
