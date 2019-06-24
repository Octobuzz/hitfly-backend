<?php
/**
 * Created by PhpStorm.
 * User: Dmitrii
 * Date: 04.04.2019
 * Time: 13:56.
 */

namespace App\Validation;

use App\Models\Collection;
use Illuminate\Support\Facades\Validator;

class CollectionValidator extends Validator
{
    public function validateDelete(string  $attr, $value, $params, \Illuminate\Validation\Validator $validator)
    {
        $data = $validator->getData();

        $user = \Auth::guard('json')->user();

        $collection = Collection::query()->find($data['id']);

        if (null === $collection) {
            throw new \Exception('Такого плейлиста не существует.');
        }

        if ($user->id === $collection->user_id) {
            return true;
        }

        return false;
    }
}
