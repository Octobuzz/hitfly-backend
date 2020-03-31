<?php
/**
 * Created by PhpStorm.
 * User: Dmitrii
 * Date: 04.04.2019
 * Time: 13:56.
 */

namespace App\Validation;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\Album;
use App\Models\Collection;
use App\Models\Favourite;
use App\Models\Lifehack;
use App\Models\Track;
use Illuminate\Support\Facades\Validator;

class FavouritesValidator extends Validator
{
    use GraphQLAuthTrait;

    public function validate(string  $attr, $value, $params, \Illuminate\Validation\Validator $validator)
    {
        $data = $validator->getData();

        switch ($data['Favourite']['favouritableType']) {
            case Favourite::TYPE_TRACK:
                $class = Track::class;
                break;
            case Favourite::TYPE_ALBUM:
                $class = Album::class;
                break;
            case Favourite::TYPE_COLLECTION:
                $class = Collection::class;
                break;
            case Favourite::TYPE_LIFE_HACK:
                $class = Lifehack::class;
                break;
            default:
                throw new \InvalidArgumentException('Не удалось определить тип избранного');
        }
        $favourite = Favourite::query()
            ->where('favouriteable_type', '=', $class)
            ->where('favouriteable_id', '=', $data['Favourite']['favouriteableId'])
            ->where('user_id', $this->getGuard()->user()->id)->first();
        if (null === $favourite) {
            return true;
        } else {
            return false;
        }
    }

    public function validateDelete(string  $attr, $value, $params, \Illuminate\Validation\Validator $validator)
    {
        $data = $validator->getData();

        switch ($data['Favourite']['favouritableType']) {
            case Favourite::TYPE_TRACK:
                $class = Track::class;
                break;
            case Favourite::TYPE_ALBUM:
                $class = Album::class;
                break;
            case Favourite::TYPE_COLLECTION:
                $class = Collection::class;
                break;
            case Favourite::TYPE_LIFE_HACK:
                $class = Lifehack::class;
                break;
            default:
                throw new \InvalidArgumentException('Не удалось определить тип избранного');
        }
        $favourite = Favourite::query()
            ->where('favouriteable_type', '=', $class)
            ->where('favouriteable_id', '=', $data['Favourite']['favouriteableId'])
            ->where('user_id', $this->getGuard()->user()->id)->first();
        if (null === $favourite) {
            return false;
        } else {
            return true;
        }
    }
}
