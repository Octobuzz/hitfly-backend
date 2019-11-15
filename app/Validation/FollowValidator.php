<?php
/**
 * Created by PhpStorm.
 * User: Dmitrii
 * Date: 04.04.2019
 * Time: 13:56.
 */

namespace App\Validation;

use App\Models\MusicGroup;
use App\Models\Watcheables;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FollowValidator extends Validator
{
    public function validate(string  $attr, $value, $params, \Illuminate\Validation\Validator $validator)
    {
        $data = $validator->getData();

        switch ($data['Follow']['FollowType']) {
            case Watcheables::TYPE_USER:
                $class = User::class;
                break;
            case Watcheables::TYPE_MUSIC_GROUP:
                $class = MusicGroup::class;
                break;
            default:
                throw new \Exception('Не удалось определить тип Подписки');
        }
        $follow = Watcheables::query()
            ->where('watcheable_type', '=', $class)
            ->where('watcheable_id', '=', $data['Follow']['FollowId'])
            ->where('user_id', Auth::user()->id)->first();
        if (null === $follow) {
            return true;
        } else {
            return false;
        }
    }

    public function followMyself(string  $attr, $value, $params, \Illuminate\Validation\Validator $validator)
    {
        $data = $validator->getData();

        if (Watcheables::TYPE_MUSIC_GROUP === $data['Follow']['FollowType'] || ($data['Follow']['FollowId'] !== Auth::user()->id && Watcheables::TYPE_USER === $data['Follow']['FollowType'])) {
            return true;
        } else {
            return false;
        }
    }

    public function validateDelete(string  $attr, $value, $params, \Illuminate\Validation\Validator $validator)
    {
        $data = $validator->getData();

        switch ($data['Follow']['FollowType']) {
            case Watcheables::TYPE_USER:
                $class = User::class;
                break;
            case Watcheables::TYPE_MUSIC_GROUP:
                $class = MusicGroup::class;
                break;
            default:
                throw new \Exception('Не удалось определить тип Подписки');
        }
        $follow = Watcheables::query()
            ->where('watcheable_type', '=', $class)
            ->where('watcheable_id', '=', $data['Follow']['FollowId'])
            ->where('user_id', Auth::user()->id)->first();
        if (null === $follow) {
            return false;
        } else {
            return true;
        }
    }
}
