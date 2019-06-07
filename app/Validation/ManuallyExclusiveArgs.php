<?php
/**
 * Валидация взаимоисключающих полей.
 */

namespace App\Validation;

use Illuminate\Support\Facades\Validator;
use mysql_xdevapi\Exception;

class ManuallyExclusiveArgs extends Validator
{
    public function validate(string  $attr, $value, $params, \Illuminate\Validation\Validator $validator)
    {
        if (empty($params)) {
            throw new  Exception('Не переданы зависимые параметры для проверки');
        }

        $data = $validator->getData();
        foreach ($params as $value) {
            if (isset($data[$value])) {
                return false;
            }
        }

        return true;
    }
}
