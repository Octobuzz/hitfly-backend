<?php
/**
 * Валидация взаимоисключающих полей.
 */

namespace App\Validation;

use Illuminate\Support\Facades\Validator;
use mysql_xdevapi\Exception;

class ManuallyExclusiveArgs extends Validator
{
    public $params;

    public function validate(string  $attr, $value, $params, \Illuminate\Validation\Validator $validator)
    {
        $this->params = array_map('trim', $params);
        if (empty($params)) {
            throw new  Exception('Не переданы зависимые параметры для проверки');
        }

        $data = $validator->getData();
        if (false === self::checkLevel($data)) {
            return false;
        }

        return true;
    }

    private function checkLevel($data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (false === self::checkLevel($value)) {
                    return false;
                }
            }
            if (in_array($key, $this->params)) {
                return false;
            }
        }
    }
}
