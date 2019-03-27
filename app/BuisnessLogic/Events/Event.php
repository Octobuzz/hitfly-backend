<?php
/**
 * Created by PhpStorm.
 * User: Dmitrii
 * Date: 26.03.2019
 * Time: 14:46
 */

namespace App\BuisnessLogic\Events;




use App\Contracts\Event\EventsContract;

class Event implements EventsContract
{

    /**
     * @param int $count
     * @return array
     */
    public function getUpcomingEvents(int $count)
    {
        // TODO: Implement getUpcomingEvents() method.
        return [
            [
                'name'=>'Название',
                'img'=>'/url',
                'link'=>'/event/url',
                'participant'=>'The Beatles',
            ],
            [
                'name'=>'Название',
                'img'=>'/url',
                'link'=>'/event/url',
                'participant'=>'The Beatles',
            ],
        ];
    }

    /**
     * события текущего месяца
     * @return array
     */
    public function getThisMonthEvents()
    {
        // TODO: Implement getThisMonthEvents() method.
        return [
            [
                'name'=>'Название',
                'img'=>'/url',
                'link'=>'/event/url',
                'participant'=>'The Beatles',
            ],
            [
                'name'=>'Название',
                'img'=>'/url',
                'link'=>'/event/url',
                'participant'=>'The Beatles',
            ],
        ];
    }
}