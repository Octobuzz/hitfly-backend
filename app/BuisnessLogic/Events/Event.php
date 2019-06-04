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
use Jenssegers\Date\Date;

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
                'img' => env('APP_URL').'/images/emails/img/disco-min.png',
                'url' => '/fake_url',
            ],
            [
                'name' => 'Название2',
                'img' => env('APP_URL').'/images/emails/img/disco-min.png',
                'url' => '/fake_url',
            ],
            [
                'name' => 'Название3',
                'img' => env('APP_URL').'/images/emails/img/disco-min.png',
                'url' => '/fake_url',
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
                'date' => Date::parse('2019-01-03')->format('d F'),
            ],
            [
                'name' => 'Название2',
                'date' => Date::parse('2019-01-05')->format('d F'),
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

    /**
     * Новые мероприятия(кроме звезды).
     *
     * @return mixed
     */
    public function getNewEvents()
    {
        // TODO: реальные мероприятия
        return [
            [
                'name' => 'Название',
                'img' => env('APP_URL').'/images/emails/img/disco-min.png',
                'url' => '/fake_url',
            ],
            [
                'name' => 'Название2',
                'img' => env('APP_URL').'/images/emails/img/disco-min.png',
                'url' => '/fake_url',
            ],
            [
                'name' => 'Название3',
                'img' => env('APP_URL').'/images/emails/img/disco-min.png',
                'url' => '/fake_url',
            ],
        ];
    }
}
