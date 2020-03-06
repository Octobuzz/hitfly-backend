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
     * @inheritDoc
     */
    public function validate(string  $attr, $value, $params, \Illuminate\Validation\Validator $validator)
    {
        $data = $validator->getData();

        switch ($data['Like']['likeableType']) {
            case Like::TYPE_LIFE_HACK:
                $class = Lifehack::class;
                break;
            default:
                throw new InvalidArgumentException('Не удалось определить тип лайка');
        }

        $like = Like::query()
            ->where('likeable_type', '=', $class)
            ->where('likeable_id', '=', $data['Like']['favouriteableId'])
            ->where('user_id', $this->getGuard()->user()->id)->first();
        if (null === $like) {
            return true;
        } else {
            return false;
        }
    }
}
