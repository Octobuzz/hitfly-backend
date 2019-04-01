<?php

namespace App\BuisnessLogic\Emails;

use App\BuisnessLogic\Events\Event;
use App\BuisnessLogic\Recommendation\Recommendation;
use App\Jobs\BirthdayCongratulationsEmailJob;
use App\Jobs\FewCommentsJob;
use App\Jobs\LongAgoNotVisitedJob;
use App\Jobs\MonthDispatchNotVisitedJob;
use App\Jobs\ReachTopJob;
use App\Jobs\RemindForEventJob;
use App\Jobs\RequestForEventJob;
use  App\User;
use Carbon\Carbon;
use App\BuisnessLogic\Playlist\Tracks;

class Notification
{
    private $listOfUsers;
    private $recommendation;
    private $tracks;
    private $events;

    public function __construct(Recommendation $recommendation, Tracks $tracks, Event $events)
    {
        $this->recommendation = $recommendation;
        $this->tracks = $tracks;
        $this->events = $events;
    }

    /**
     * очередь писем с поздравлениями на день рождения.
     */
    public function birthdayCongratulation()
    {
        $this->listOfUsers = $this->getUsersBirthdayToday();
        $recommend = $this->recommendation;
        foreach ($this->listOfUsers as $user) {
            dispatch(new BirthdayCongratulationsEmailJob($user, $recommend->getBirthdayPlayLists($user)))->onQueue('low');
        }
    }

    /**
     * Получает пользователей у которых сегодня день рождения.
     *
     * @return User[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    protected function getUsersBirthdayToday()
    {
        return User::query()->whereMonth('birthday', Carbon::today()->month)->whereDay('birthday', Carbon::today()->day)->whereNotNull('email')->get();
    }

    public function fewComments()
    {
        $users = $this->getUsersWithFewComments();

        $tracks = new Tracks();
        foreach ($users as $user) {
            dispatch(new FewCommentsJob($user, $tracks->getNewTracks(3)))->onQueue('low');
        }
    }

    /**
     * список пользователей, которые за неделю оставили мало отзывов.
     *
     * @return User[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    private function getUsersWithFewComments()
    {
        $users = User::query()->whereIn('id', function ($query) {
            $query->select('user_id')->from('admin_role_users')->where('role_id', '=', '4')->whereNotIn('user_id', function ($query2) {
                $query2->select('user_id')
                    ->from('comments')
                    ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfDay()])
                    ->groupBy('user_id')
                    ->havingRaw('count(`user_id`) >= ?', [env('FEW_FEEDBACK_PERIOD')]);
            });
        }
            )->get();

        return $users;
    }

    /**
     * давно не посещал сайт
     */
    public function longAgoNotVisited()
    {
        $users = $this->getUsersLongAgoNotVisited();

        foreach ($users['days7'] as $user) {
            dispatch(new LongAgoNotVisitedJob(7, $user, $this->events->getUpcomingEvents(3), $this->recommendation->getNewUserPlayList(2), $this->tracks->getTopTrack(5)))->onQueue('low');
        }
        foreach ($users['days30'] as $user) {
            dispatch(new LongAgoNotVisitedJob(30, $user, $this->events->getUpcomingEvents(3), $this->recommendation->getNewUserPlayList(2), $this->tracks->getTopTrack(5)))->onQueue('low');
        }
    }

    /**
     * давно непосещал сайт 7 и 30 дней.
     *
     * @return array
     */
    private function getUsersLongAgoNotVisited()
    {
        $return = [];

        $return['days7'] = User::query()->whereBetween('last_login', [Carbon::now()->subDays(7)->startOfDay(), Carbon::now()->subDays(7)->endOfDay()])->get();
        $return['days30'] = User::query()->whereBetween('last_login', [Carbon::now()->subDays(30)->startOfDay(), Carbon::now()->subDays(30)->endOfDay()])->get();

        return $return;
    }

    /**
     * давно не посещал сайт
     */
    public function everyMonthDispatchNotVisited()
    {
        $users = $this->getMonthDispatchNotVisited();

        foreach ($users as $user) {
            dispatch(new MonthDispatchNotVisitedJob($user, $this->events->getUpcomingEvents(3), $this->recommendation->getNewUserPlayList(2), $this->tracks->getTopTrack(5)))->onQueue('low');
        }
    }

    private function getMonthDispatchNotVisited()
    {
        return User::query()->where('last_login', '<', Carbon::now()->subDays(30)->startOfDay())->get();
    }

    /**
     * напоминание о событии.
     */
    public function remindForEvent()
    {
        $users = $this->getApplicantsForEvent();

        foreach ($users as $user) {
            $events = $this->events->getUpcomingEventsForUser($user);
            foreach ($events as $event) {
                dispatch(new RemindForEventJob($event, $user, $this->events->getUpcomingEvents(3)))->onQueue('low');
            }
        }
    }

    private function getApplicantsForEvent()
    {
        // TODO: выборка пользователей подавших заявку на мероприятие
        return User::query()->where('id', '=', 1)->get();
    }

    /**
     * подача заявки на участие в мероприятии.
     */
    public function requestForEvent(User $user, $event)
    {
        dispatch(new RequestForEventJob($user, $this->events->getEventById(1), $this->events->getUpcomingEvents(3)))->onQueue('low');
    }

    /**
     * попадение в топ 20.
     */
    public function reachTop($topCount = 20)
    {
        $tracks = $this->tracks->getTopTrack($topCount);
        foreach ($tracks as $track) {
            //TODO реальный урл к топ20
            $topUrl = '/url';
            dispatch(new ReachTopJob($track, $topUrl, $this->events->getUpcomingEvents(3), $topCount))->onQueue('low');
        }
    }
}
