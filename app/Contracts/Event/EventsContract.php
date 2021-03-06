<?php

namespace App\Contracts\Event;

use App\User;

interface EventsContract
{
    /**
     * @param int $count
     *
     * @return array
     */
    public function getUpcomingEvents(int $count);

    public function getThisMonthEvents();

    public function getUpcomingEventsForUser(User $user);

    public function getEventById(int $id);

    /**
     * Новые мероприятия(кроме звезды).
     *
     * @return mixed
     */
    public function getNewEvents();

    /**
     * получить важные события(новости, статьи блога, мероприятия) с пометкой "важно".
     *
     * @param int $count
     *
     * @return array
     */
    public function getImportantEvents(int $count): array;
}
