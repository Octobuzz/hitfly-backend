<?php
/**
 * Created by PhpStorm.
 * User: Dmitrii
 * Date: 04.04.2019
 * Time: 13:56.
 */

namespace App\Validation;

use App\Models\MusicGroup;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class MusicGroupValidator extends Validator
{
    public function validateDelete(string  $attr, $value, $params, \Illuminate\Validation\Validator $validator)
    {
        $data = $validator->getData();

        $musicGroup = MusicGroup::query()->find($data['id']);

        if (null === $musicGroup) {
            throw new \Exception('Такой группы не существует.');
        }

        return Gate::allows('delete', $musicGroup);
    }
}
