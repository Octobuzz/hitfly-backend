<?php

namespace App\Helpers;

class DateHelpers
{
    /**
     * возвращает месяц в будущем времени.
     *
     * @param bool $month
     *
     * @return string
     */
    public static function getNameMonthInFuture($month = false): string
    {
        if (false === $month) {
            $month = date('m', time());
        } else {
            $month = date('m', strtotime($month));
        }
        switch ($month) {
            case '01':
                return 'Январе';
            case '02':
                return 'Феврале';
            case '03':
                return 'Марте';
            case '04':
                return 'Апреле';
            case '05':
                return 'Мае';
            case '06':
                return 'Июне';
            case '07':
                return 'Июле';
            case '08':
                return 'Августе';
            case '09':
                return 'Сентябре';
            case '10':
                return 'Октябре';
            case '11':
                return 'Ноябре';
            case '12':
                return 'Декабре';

                default: return 'DateFormatError';
        }
    }

    /**
     * @param $number
     * @param $ending_arr
     * @return string
     */
    public static function getNumEnding($number, $ending_arr) :string
    {
        $number = $number % 100;
        if ($number >= 11 && $number <= 19) {
            $ending = $ending_arr[2];
        } else {
            $i = $number % 10;
            switch ($i) {
                case (1): $ending = $ending_arr[0];
                    break;
                case (2):
                case (3):
                case (4): $ending = $ending_arr[1];
                    break;
                default: $ending = $ending_arr[2];
            }
        }
        return $ending;
    }
}
