<?php

namespace App\Listeners;

use App\BuisnessLogic\Emails\Notification;
use App\BuisnessLogic\Notify\BaseNotifyMessage;
use App\Dictionaries\RoleDictionary;
use App\Events\CompletedTaskEvent;
use App\Events\CreatedTopFiftyEvent;
use App\Events\Track\TrackPublishEvent;
use App\Events\User\ChangeLevelEvent;
use App\Events\User\DecreaseRoleEvent;
use App\Events\User\IncreaseRoleEvent;
use App\Models\Album;
use App\Models\Collection;
use App\Models\Comment;
use App\Models\Favourite;
use App\Models\Role;
use App\Models\Track;
use App\Models\Watcheables;
use App\Notifications\BaseNotification;
use App\User;
use GatewayWorker\Lib\Gateway;
use Exception;
use Illuminate\Support\Facades\Log;

class NotificationEventSubscriber
{
    private $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        // Подписка на EVENT создание трека.
        $events->listen(TrackPublishEvent::class, self::class.'@createTrack'); // Новая песня/ альбом/ плейлсит Название у Имя пользователя
        $events->listen('eloquent.created: '.Album::class, self::class.'@createAlbum'); // Новая песня/ альбом/ плейлсит Название у Имя пользователя
        $events->listen('eloquent.created: '.Collection::class, self::class.'@createCollection'); // Новая песня/ альбом/ плейлсит Название у Имя пользователя
        $events->listen('eloquent.created: '.Favourite::class, self::class.'@favourite'); // Кирилл Савинов оценил(а) вашу песню Драмы больше нет
        $events->listen('eloquent.created: '.Comment::class, self::class.'@criticReview'); // Имя звезды оставил(а) отзыв на песню Название
        $events->listen('eloquent.created: '.Watcheables::class, self::class.'@createWatcheables'); // У вас новый поклонник Имя пользователя
        $events->listen(CompletedTaskEvent::class, self::class.'@completedTask'); // Вы выполнили задание Название и получили 300 бонусов
        $events->listen(ChangeLevelEvent::class, self::class.'@changeLevel'); // Поздравляем! Вы получили новый уровень/ статус Любитель
//        $events->listen(CreatedTopFiftyEvent::class, self::class.'@createdTopFifty'); // Поздравляем! Ваша песня Название попала в ТОП 20
        $events->listen(IncreaseRoleEvent::class, self::class.'@increaseRole'); // Поздравляем! Вы получили новый статус Критик
        $events->listen(DecreaseRoleEvent::class, self::class.'@decreaseRole'); // К сожалению, мы были вынуждены понизить ваш статус
    }

    /**
     * ОТправка нотификации всем
     *
     * @param string $type
     * @param array  $messageData
     * @param array  $exclude_client_id
     */
    private function sendBroadCast(string $type, array $messageData, $exclude_client_id = null)
    {
        try {
            Gateway::sendToAll(json_encode([
                'type' => 'notification',
                'data' => [
                    'type' => $type,
                    'date' => (new \DateTime())->format(\DateTime::ISO8601),
                    'messageData' => $messageData,
                ],
            ]), null, $exclude_client_id);
        } catch (Exception $exception) {
            Log::alert($exception->getMessage(), $exception->getTrace());
        }
    }

    /**
     * Поставили лайк.
     *
     * @param Favourite $favourite
     */
    public function favourite(Favourite $favourite)
    {
        /** @var User $user */
        $user = $favourite->user;
        /** @var Favourite $favoriteId */
        $favoriteId = null;
        /** @var User | null $notifyUser */
        $notifyUser = null;
        $title = null;
        $type = null;

        switch (get_class($favourite->favouriteable()->getRelated())) {
            case Album::class:
                $title = $favourite->album->title;
                $type = 'Album';
                $notifyUser = $favourite->album->user;
                $favoriteId = $favourite->album->id;
                break;
            case Track::class:
                $title = $favourite->track->track_name;
                $type = 'Track';
                $notifyUser = $favourite->track->user;
                $favoriteId = $favourite->track->id;
                break;
            default:
                return;
        }

        if (null !== $notifyUser && $user->id !== $notifyUser->id) {
            $messageData = [
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'avatar' => $user->avatar,
                ],
                'rated' => [
                    'id' => $favoriteId,
                    'type' => $type,
                    'title' => $title,
                ],
            ];

            $baseNotifyMessage = new BaseNotifyMessage('rating', $messageData);
            $notifyUser->notify(new BaseNotification($baseNotifyMessage));
        }
    }

    public function createWatcheables(Watcheables $watcheables)
    {
        /** @var User $user */
        $user = $watcheables->watcher()->first();
        /** @var User | null $notifyUser */
        $notifyUser = null;

        if (User::class !== get_class($watcheables->watcheable()->getRelated())) {
            return;
        }

        $notifyUser = $watcheables->user()->first();

        if (null !== $notifyUser) {
            $messageData = [
                'follower' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'avatar' => $user->avatar,
                ],
            ];
            $baseNotifyMessage = new BaseNotifyMessage('new-follower', $messageData);
            $notifyUser->notify(new BaseNotification($baseNotifyMessage));
        }
    }

    public function createTrack(TrackPublishEvent $createdEvent)
    {
        $track = $createdEvent->getTrack();

        /** @var User $user */
        $user = $track->user;
        $exclude_client_id = [];
        if (null !== $user->userNotification) {
            $exclude_client_id[] = $user->userNotification->token_web_socket;
        }
        try {
            $user = $track->user;
            $messageData = [
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'avatar' => $user->avatar,
                ],
                'music' => [
                    'type' => 'Track',
                    'id' => $track->id,
                    'title' => $track->track_name,
                ],
            ];

            $messageNotify = new BaseNotifyMessage('new-music', $messageData);
            $users = $this->notification->getWatchingUsers($user->id);
            foreach ($users as $user) {
                $user->notify(new BaseNotification($messageNotify));
            }

            $this->notification->newFavouriteTrackNotification($track);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage(), $exception);
        }
    }

    public function createAlbum(Album $album)
    {
        /** @var User $user */
        $user = $album->user;
        $exclude_client_id = [];
        if (null !== $user->userNotification) {
            $exclude_client_id[] = $user->userNotification->token_web_socket;
        }

        $messageData = [
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'avatar' => $user->avatar,
            ],
            'music' => [
                'type' => 'Album',
                'id' => $album->id,
                'title' => $album->title,
            ],
        ];
        $messageNotify = new BaseNotifyMessage('new-music', $messageData);
        $users = $this->notification->getWatchingUsers($user->id);
        foreach ($users as $user) {
            $user->notify(new BaseNotification($messageNotify));
        }
        $this->notification->newFavouriteTrackNotification($album);
    }

    public function createCollection(Collection $collection)
    {
        /** @var User $user */
        $user = $collection->user;
        if (null === $user) {
            $user = $collection->load('user');
        }
        $messageData = [
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'avatar' => $user->avatar,
            ],
            'music' => [
                'type' => 'Collection',
                'id' => $collection->id,
                'title' => $collection->title,
            ],
        ];
        $messageNotify = new BaseNotifyMessage('new-music', $messageData);
        $users = $this->notification->getWatchingUsers($user->id);
        foreach ($users as $user) {
            $user->notify(new BaseNotification($messageNotify));
        }
    }

    public function criticReview(Comment $comment)
    {
        $notifyUser = null;
        $user = $comment->user;
        $title = null;

        switch (get_class($comment->commentable()->getRelated())) {
            case Track::class:
                $notifyUser = $comment->track->user;
                $title = $comment->track_name;
                break;
            default:
                return;
        }

        if (null !== $notifyUser) {
            $messageData = [
                'reviewer' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'avatar' => $user->avatar,
                ],
                'track' => [
                    'id' => $comment->commentable_id,
                    'title' => $title,
                ],
            ];

            $baseNotifyMessage = new BaseNotifyMessage('track-reviewed', $messageData);
            $notifyUser->notify(new BaseNotification($baseNotifyMessage));
        }
    }

    /**
     * @param CompletedTaskEvent $event
     */
    public function completedTask(CompletedTaskEvent $event)
    {
        $user = $event->getUser();
        $messageData = [
            'task' => [
                'title' => $event->getName(),
                'bonus' => $event->getPoint(),
            ],
        ];

        $baseNotifyMessage = new BaseNotifyMessage('completed-task', $messageData);
        $user->notify(new BaseNotification($baseNotifyMessage));
    }

    /**
     * @param ChangeLevelEvent $event
     */
    public function changeLevel(ChangeLevelEvent $event)
    {
        $user = $event->getUser();
        $messageData = [
            'level' => $event->getLevel(),
        ];

        $baseNotifyMessage = new BaseNotifyMessage('new-level', $messageData);
        $user->notify(new BaseNotification($baseNotifyMessage));
    }

    public function createdTopFifty(CreatedTopFiftyEvent $createdTopFifty)
    {
        $idsTrack = $createdTopFifty->getIdsTrack();
        if (count($idsTrack) > 50) {
            $chunks = array_chunk($idsTrack, 50, true);
            $topTwenty = $chunks[0];
        } else {
            $topTwenty = $idsTrack;
        }
        $tracks = Track::query()->whereIn('id', $topTwenty)->get();

        foreach ($tracks as $track) {
            $messageData = [
                'track' => [
                    'id' => $track->id,
                    'title' => $track->track_name,
                    'cover' => $track->getImageUrl(),
                ],
                'top' => 50,
            ];

            /** @var User $user */
            $user = $track->user;
            $baseNotifyMessage = new BaseNotifyMessage('track-in-top', $messageData);
            $user->notify(new BaseNotification($baseNotifyMessage));
        }
    }

    public function increaseRole(IncreaseRoleEvent $increaseRoleEvent): void
    {
        $user = $increaseRoleEvent->getUser();
        $role = $increaseRoleEvent->getRole();

        $messageData = [
            'status' => [
                'name' => $role->name,
                'slug' => $role->slug,
            ],
        ];

        $baseNotifyMessage = new BaseNotifyMessage('new-status', $messageData);
        $user->notify(new BaseNotification($baseNotifyMessage));
        $this->notification->newStatusNotification($role->name, $user);
    }

    public function decreaseRole(DecreaseRoleEvent $event)
    {
        $role = $event->getRole();
        $user = $event->getUser();
        $newStatus = RoleDictionary::getPreviousRoleSlug($role->slug);
        $oldRole = Role::query()->where('slug', '=', $newStatus)->first();
        $this->notification->decreaseStatusNotification($role->name, $oldRole->name, $user);
    }
}
