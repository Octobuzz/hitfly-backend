<?php

namespace App\Validation;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\Album;
use App\Models\Collection;
use App\Models\Favourite;
use App\Models\Lifehack;
use App\Models\Like;
use App\Models\Track;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class LikeValidator extends Validator
{
    use GraphQLAuthTrait;

    public function unique(string  $attr, $value, $params, \Illuminate\Validation\Validator $validator)
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

    public function delete(string  $attr, $value, $params, \Illuminate\Validation\Validator $validator)
    {
        $data = $validator->getData();

        switch ($data['Like']['likeableType']) {
            case Favourite::TYPE_TRACK:
                $class = Track::class;
                break;
            case Favourite::TYPE_ALBUM:
                $class = Album::class;
                break;
            case Favourite::TYPE_COLLECTION:
                $class = Collection::class;
                break;
            default:
                throw new InvalidArgumentException('Не удалось определить тип избранного');
        }

        $like = Like::query()
            ->where('likeable_type', '=', $class)
            ->where('likeable_id', '=', $data['Like']['likeableId'])
            ->where('user_id', $this->getGuard()->user()->id)->first();

        return null !== $like;
    }
}
