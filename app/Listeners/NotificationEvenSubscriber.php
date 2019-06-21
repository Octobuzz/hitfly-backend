<?php

namespace App\Listeners;

use App\BuisnessLogic\Notify\BaseNotifyMessage;
use App\Events\CompletedTaskEvent;
use App\Events\User\ChangeLevelEvent;
use App\Models\Album;
use App\Models\Collection;
use App\Models\Comment;
use App\Models\Favourite;
use App\Models\Track;
use App\Models\Watcheables;
use App\Notifications\BaseNotification;
use App\User;
use GatewayWorker\Lib\Gateway;
use Illuminate\Support\Facades\Log;

class NotificationEvenSubscriber
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        // Подписка на EVENT создание трека.
        $events->listen('eloquent.created: '.Track::class, self::class.'@createTrack'); // Новая песня/ альбом/ плейлсит Название у Имя пользователя
        $events->listen('eloquent.created: '.Album::class, self::class.'@createAlbum'); // Новая песня/ альбом/ плейлсит Название у Имя пользователя
        $events->listen('eloquent.created: '.Collection::class, self::class.'@createCollection'); // Новая песня/ альбом/ плейлсит Название у Имя пользователя
        $events->listen('eloquent.created: '.Favourite::class, self::class.'@favourite'); // Кирилл Савинов оценил(а) вашу песню Драмы больше нет
        $events->listen('eloquent.created: '.Comment::class, self::class.'@criticReview'); // Имя звезды оставил(а) отзыв на песню Название
        $events->listen('eloquent.created: '.Watcheables::class, self::class.'@createWatcheables'); // У вас новый поклонник Имя пользователя
        $events->listen(CompletedTaskEvent::class, self::class.'@completedTask'); // Вы выполнили задание Название и получили 300 бонусов
        $events->listen(ChangeLevelEvent::class, self::class.'@changeLevel'); // Поздравляем! Вы получили новый уровень/ статус Любитель
    }

    /**
     * ОТправка нотификации всем
     *
     * @param string $type
     * @param array  $messageData
     */
    private function sendBroadCast(string $type, array $messageData)
    {
        try {
            Gateway::sendToAll(json_encode([
                'type' => 'notification',
                'data' => [
                    'type' => $type,
                    'date' => (new \DateTime())->format(\DateTime::ISO8601),
                    'messageData' => $messageData,
                ],
            ]));
        } catch (\Exception $exception) {
            Log::alert($exception->getMessage(), $exception);
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
        $favoriteId = 'favouriteable_id';
        /** @var User | null $notifyUser */
        $notifyUser = null;
        $title = null;
        $type = null;

        switch (get_class($favourite->favouriteable()->getRelated())) {
            case Album::class:
                $title = $favourite->album->track_name;
                $type = 'Album';
                $notifyUser = $favourite->album->user;
                break;
            case Track::class:
                $title = $favourite->album->name;
                $type = 'Track';
                $notifyUser = $favourite->track->user;
                break;
            default:
                return;
        }

        if (null !== $notifyUser) {
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
        $user = $watcheables->user;
        /** @var User | null $notifyUser */
        $notifyUser = null;

        switch (get_class($watcheables->watcheable()->getRelated())) {
            case User::class:
                $notifyUser = $watcheables->user();
                break;
            default:
                return;
        }

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

    public function createTrack(Track $track)
    {
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
        $this->sendBroadCast('new-music', $messageData);
    }

    public function createAlbum(Album $album)
    {
        $user = $album->user;
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
        $this->sendBroadCast('new-music', $messageData);
    }

    public function createCollection(Collection $collection)
    {
        $user = $collection->user;
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
        $this->sendBroadCast('new-music', $messageData);
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

        $baseNotifyMessage = new BaseNotifyMessage('completed-task', $messageData);
        $user->notify(new BaseNotification($baseNotifyMessage));
    }
}
