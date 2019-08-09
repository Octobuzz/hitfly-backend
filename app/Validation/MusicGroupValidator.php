<?php
/**
 * Created by PhpStorm.
 * User: Dmitrii
 * Date: 04.04.2019
 * Time: 13:56.
 */

namespace App\Validation;

use App\Models\Album;
use App\Models\MusicGroup;
use App\Models\Track;
use Illuminate\Support\Facades\Validator;

class MusicGroupValidator extends Validator
{
    public function validateDelete(string  $attr, $value, $params, \Illuminate\Validation\Validator $validator)
    {
        $data = $validator->getData();

        $user = \Auth::guard('json')->user();
        $musicGroup = MusicGroup::query()->find($data['id']);

        if (null === $musicGroup) {
            throw new \Exception('Такой группы не существует.');
        }

        if ($user->id === $musicGroup->creator_group_id) {
            return true;
        }

        return false;
    }

}
