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

    /**
     * получает дату периода, от текущей.
     * @param String $period
     * @return string
     */
    public static function getPeriod(String $period): string
    {
        $now = \Carbon\Carbon::now();

        switch ($period) {
            case 'week':
                $date = $now->subWeek()->format('Y-m-d');
                break;
            case 'month':
                $date = $now->subMonth()->format('Y-m-d');
                break;
            case 'year':
                $date = $now->subYear()->format('Y-m-d');
                break;
            default:
                $date = $now->subWeek()->format('Y-m-d');
        }

        return $date;
    }
}
