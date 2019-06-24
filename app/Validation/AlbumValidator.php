<?php
/**
 * Created by PhpStorm.
 * User: Dmitrii
 * Date: 04.04.2019
 * Time: 13:56.
 */

namespace App\Validation;

use App\Models\Album;
use Illuminate\Support\Facades\Validator;

class AlbumValidator extends Validator
{
    public function validateDelete(string  $attr, $value, $params, \Illuminate\Validation\Validator $validator)
    {
        $data = $validator->getData();

        $user = \Auth::guard('json')->user();

        $album = Album::query()->find($data['id']);

        if (null === $album) {
            throw new \Exception('Такого альбома не существует.');
        }

        if ($user->id === $album->user_id) {
            return true;
        }

        return false;
    }
}
