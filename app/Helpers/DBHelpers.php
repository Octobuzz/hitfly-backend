<?php
/**
 * Created by PhpStorm.
 * User: Dmitrii
 * Date: 05.04.2019
 * Time: 15:47.
 */

namespace App\Helpers;

class DBHelpers
{
    /**
     * ключи массива к snake_case.
     *
     * @param array $arr
     *
     * @return array
     */
    public static function arrayKeysToSnakeCase(array $arr): array
    {
        $return = [];
        foreach ($arr as $k => $v) {
            if (is_array($v)) {
                $return[$k] = self::arrayKeysToSnakeCase($v);
            } else {
                $return[self::camelCaseToSnakeCase($k)] = $v;
            }
        }

        return $return;
    }

    /**
     * строку к snake_case.
     *
     * @param string $string
     *
     * @return string
     */
    public static function camelCaseToSnakeCase(String $string): string
    {
        return snake_case($string);
    }
}
