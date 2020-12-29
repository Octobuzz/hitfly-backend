<?php
/**
 * Валидация взаимоисключающих полей.
 */

namespace App\Validation;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * проверка корректности связки логин + пароль
 * Class LoginPasswordCorrect.
 */
class LoginPasswordCorrect extends Validator
{
    public $params;

    public function validate(string  $attr, $value, $params, \Illuminate\Validation\Validator $validator)
    {
        $data = $validator->getData();
        $user = User::query()->where('email', '=', trim($data['email']))->first();

        if (null === $user) {
            return false;
        }

        if (false === Hash::check($data['password'], $user->password)) {
            return false;
        }

        return true;
    }
}
