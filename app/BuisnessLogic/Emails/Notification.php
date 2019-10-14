<?php

namespace App\BuisnessLogic\Emails;

use App\BuisnessLogic\Events\Event;
use App\BuisnessLogic\Promo\PromoCode;
use App\BuisnessLogic\Recommendation\Recommendation;
use App\Dictionaries\RoleDictionary;
use App\Jobs\BirthdayCongratulationsEmailJob;
use App\Jobs\CommentCreatedJob;
use App\Jobs\DecreaseLevelJob;
use App\Jobs\DecreaseStatusJob;
use App\Jobs\FewCommentsJob;
use App\Jobs\FewCommentsMonthJob;
use App\Jobs\LongAgoNotVisitedJob;
use App\Jobs\MonthDispatchNotVisitedJob;
use App\Jobs\NewEventNotificationJob;
use App\Jobs\NewFavouriteTrackJob;
use App\Jobs\NewStatusJob;
use App\Jobs\ReachTopJob;
use App\Jobs\RemindForEventJob;
use App\Jobs\RequestForEventJob;
use App\Mail\BirthdayCongratulation;
use App\Mail\FewComments;
use App\Mail\LongAgoNotVisited;
use App\Models\Album;
use App\Models\Comment;
use App\Models\Track;
use App\Models\Watcheables;
use  App\User;
use Carbon\Carbon;
use App\BuisnessLogic\Playlist\Tracks;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class Notification
{
    private const MONTH = 'month';
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
        $discount = new PromoCode();
        foreach ($this->listOfUsers as $user) {
            dispatch(new BirthdayCongratulationsEmailJob($user, $discount->getYearSubscribeDiscount(), $discount->getYearSubscribePromoCode(), $this->getBirthdayVideo()))->onQueue('low');
        }
    }

    /**
     * Получает пользователей у которых сегодня день рождения.
     *
     * @return User[]|Builder[]|Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    protected function getUsersBirthdayToday()
    {
        return User::query()->whereMonth('birthday', Carbon::today()->month)->whereDay('birthday', Carbon::today()->day)->whereNotNull('email')->get();
    }

    protected function getBirthdayVideo()
    {
        // TODO получать реальное видео
        return [
            'url' => '/fake_url',
            'nameStar' => 'STAR_NAME', //имя звезды
            'preview_img' => config('app.url').'/images/emails/img/video.png',
        ];
    }

    public function fewComments($period = 'month')
    {
        if (self::MONTH === $period) {
            $startPeriod = Carbon::now()->startOfMonth();
            $commentCount = 1;
        } else {
            $startPeriod = Carbon::now()->startOfWeek();
            $commentCount = env('FEW_FEEDBACK_PERIOD');
        }
        $users = $this->getUsersWithFewComments($startPeriod, $commentCount);

        $tracks = new Tracks();
        foreach ($users as $user) {
            //return new FewComments($user, $tracks->getNewTracks(4));
            if (self::MONTH === $period) {
                dispatch(new FewCommentsMonthJob($user, $tracks->getNewTracks(4)))->onQueue('low');
            } else {
                dispatch(new FewCommentsJob($user, $tracks->getNewTracks(4)))->onQueue('low');
            }
        }
    }

    /**
     * список пользователей, которые за неделю оставили мало отзывов.
     *
     * @return User[]|Builder[]|Collection
     */
    private function getUsersWithFewComments($startPeriod, $commentCount)
    {
        $users = User::query()->whereIn('id', function ($query) use ($startPeriod, $commentCount) {
            $query->select('user_id')->from('admin_role_users')->whereIn('role_id', function ($query2) use ($startPeriod, $commentCount) {
                $query2->select('id')
                        ->from('admin_roles')
                        ->whereIn('slug', [RoleDictionary::ROLE_CRITIC, RoleDictionary::ROLE_STAR]);
            }
                )->whereNotIn('user_id', function ($query2) use ($startPeriod, $commentCount) {
                    $query2->select('user_id')
                    ->from('comments')
                    ->whereBetween('created_at', [$startPeriod, Carbon::now()->endOfDay()])
                    ->groupBy('user_id')
                    ->havingRaw('count(`user_id`) >= ?', [$commentCount]);
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
            dispatch(new LongAgoNotVisitedJob(7, $user, $this->events->getThisMonthEvents(2), $this->events->getImportantEvents(1), $this->tracks->getTopTrack(4)))->onQueue('low');
        }
        foreach ($users['days30'] as $user) {
            dispatch(new LongAgoNotVisitedJob(30, $user, $this->events->getThisMonthEvents(2), $this->events->getImportantEvents(1), $this->tracks->getTopTrack(4)))->onQueue('low');
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
        $stars = User::query()->select('users.id')->leftJoin('admin_role_users', 'users.id', '=', 'admin_role_users.user_id')
            ->leftJoin('admin_roles', 'admin_role_users.role_id', '=', 'admin_roles.id')
            ->where('admin_roles.slug', '=', RoleDictionary::ROLE_STAR);
        $query = User::query()->whereNotIn('users.id', $stars);
        $query2 = clone $query;
        $return['days7'] = $query->whereBetween('last_login', [Carbon::now()->subDays(7)->startOfDay(), Carbon::now()->endOfDay()])->get();
        $return['days30'] = $query2->where('last_login', '<=', Carbon::now()->subDays(30)->endOfDay())->get();
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
        $stars = User::query()->select('users.id')->leftJoin('admin_role_users', 'users.id', '=', 'admin_role_users.user_id')
            ->leftJoin('admin_roles', 'admin_role_users.role_id', '=', 'admin_roles.id')
            ->where('admin_roles.slug', '=', RoleDictionary::ROLE_STAR);
        $query = User::query()->whereNotIn('users.id', $stars);

        return $query->where('last_login', '<', Carbon::now()->subDays(30)->startOfDay())->get();
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
                dispatch(new RemindForEventJob($event, $user, $this->events->getThisMonthEvents()))->onQueue('low');
            }
        }
    }

    private function getApplicantsForEvent()
    {
        // TODO: выборка пользователей подавших заявку на мероприятие
        return User::query()->where('id', '=', 31)->get();
    }

    /**
     * подача заявки на участие в мероприятии.
     */
    public function requestForEvent(User $user, $event)
    {
        dispatch(new RequestForEventJob($user, $this->events->getEventById(1), $this->events->getThisMonthEvents()))->onQueue('low');
    }

    /**
     * попадение в топ 20.
     * получает топ, и рассылает письма авторам треков.
     */
    public function reachTop($topCount = 50)
    {
        $tracks = $this->tracks->getTopTrack($topCount);
        foreach ($tracks as $track) {
            //TODO реальный урл к топ20
            $topUrl = config('app.url').'/top50';
            dispatch(new ReachTopJob($track, $topUrl, $topCount))->onQueue('low');
        }
    }

    /**
     * Нотификации о новом мероприятии(кроме звезды).
     */
    public function newEventNotification()
    {
        $users = $this->getSubscribersToEvent();
        $events = $this->events->getNewEvents();
        if (!empty($events)) {
            foreach ($users as $user) {
                dispatch(new NewEventNotificationJob($events, $user))->onQueue('low');
            }
        }
    }

    /**
     * получить список подписчиков на события.
     *
     * @return User[]|Builder[]|Collection
     */
    private function getSubscribersToEvent()
    {
        // TODO: выборка пользователей подписаных на рассылку о событиях
        return User::query()->where('id', '=', 214)->get();
    }

    /**
     * Нотификации о новом отзыве.
     */
    public function newCommentNotification($commentId)
    {
        $comment = Comment::query()->find($commentId);
        dispatch(new CommentCreatedJob($comment))->onQueue('low');
    }

    /**
     * Новый статус.
     */
    public function newStatusNotification($status, User $user)
    {
        dispatch(new NewStatusJob($status, $user))->onQueue('low');
    }

    /**
     * понижение статуса.
     */
    public function decreaseStatusNotification($decreaseStatus, $oldStatus, User $user)
    {
        dispatch(new DecreaseStatusJob($decreaseStatus, $oldStatus, $user))->onQueue('low');
    }

    /**
     * понижение уровня.
     */
    public function decreaseLevelNotification($decreaseStatus, $oldStatus, User $user)
    {
        dispatch(new DecreaseLevelJob($decreaseStatus, $oldStatus, $user))->onQueue('low');
    }

    /**
     * новый трек у любимого исполнителя.
     * Ищет всех подписавшихся на автора трека и рассылает письма.
     *
     * @throws Exception
     */
    public function newFavouriteTrackNotification($track)
    {
        $users = $this->getWatchingUsersByTrack($track);
        switch (get_class($track)) {
            case Track::class:
                $essence = 'track';
                $url = config('app.url').'/user/'.$track->user->id.'/music';
                break;
            case Album::class:
                $essence = 'album';
                $url = config('app.url').'/user/'.$track->user->id.'/album/'.$track->id;
                break;
            default:
                throw new Exception('неизвестный тип');
        }

        foreach ($users as $user) {
            dispatch(new NewFavouriteTrackJob($track->user->username, $track->getName(), $essence, $user, $url))->onQueue('low');
        }
    }

    /**
     * получение наблюдающих пользователей по треку или альбому.
     *
     * @param Track $track
     *
     * @return array
     */
    public function getWatchingUsersByTrack($track)
    {
        $watchableUserId = $track->user->id;

        return $this->getWatchingUsers($watchableUserId);
    }

    /**
     * получение пользователей следящим за исполнителем
     *
     * @param $userId
     *
     * @return User[]
     */
    public function getWatchingUsers($userId)
    {
        $userList = [];
        $watchableList = Watcheables::query()
            ->where('watcheable_type', User::class)
            ->where('watcheable_id', $userId)->get();
        foreach ($watchableList as $watch) {
            $userList[] = $watch->watcher;
        }

        return $userList;
    }
}
