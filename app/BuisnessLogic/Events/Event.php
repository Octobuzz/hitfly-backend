<?php
/**
 * Created by PhpStorm.
 * User: Dmitrii
 * Date: 26.03.2019
 * Time: 14:46.
 */

namespace App\BuisnessLogic\Events;

use App\Contracts\Event\EventsContract;
use App\User;

class Event implements EventsContract
{
    /**
     * @param int $count
     *
     * @return array
     */
    public function getUpcomingEvents(int $count)
    {
        // TODO: Implement getUpcomingEvents() method.
        return [
            [
                'name' => 'Название',
                'img' => '/url',
                'link' => '/event/url',
                'participant' => 'The Beatles',
            ],
            [
                'name' => 'Название',
                'img' => '/url',
                'link' => '/event/url',
                'participant' => 'The Beatles',
            ],
        ];
    }

    /**
     * события текущего месяца.
     *
     * @return array
     */
    public function getThisMonthEvents()
    {
        // TODO: Implement getThisMonthEvents() method.
        return [
            [
                'name' => 'Название',
                'img' => '/url',
                'link' => '/event/url',
                'participant' => 'The Beatles',
            ],
            [
                'name' => 'Название',
                'img' => '/url',
                'link' => '/event/url',
                'participant' => 'The Beatles',
            ],
        ];
    }

    /**
     * ближайшие события для пользователя.
     *
     * @param User $user
     *
     * @return array
     */
    public function getUpcomingEventsForUser(User $user)
    {
        // TODO: Implement getUpcomingEventsForUser() method.

        return [
            [
                'name' => 'Название',
                'date' => '2019-01-03',
            ],
            [
                'name' => 'Название2',
                'date' => '2019-01-05',
            ],
        ];
    }

    public function getEventById(int $id)
    {
        // TODO: Implement getEventById() method.
        return[
            'name' => 'Битва музыкантов',
            'link' => '/url',
        ];
    }
}
