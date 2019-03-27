<?php

namespace App\Contracts\Event;



interface EventsContract
{
    /**
     * @param int $count
     * @return array
     */
    public function getUpcomingEvents(int $count);

    public function getThisMonthEvents();

    
}