<?php

namespace App\Listeners;

use App\BuisnessLogic\Notify\BaseNotifyMessage;
use App\Events\EntranceInAppEvent;
use App\Events\ListeningTenTrackEvent;
use App\Models\Album;
use App\Models\Collection;
use App\Models\Comment;
use App\Models\Favourite;
use App\Models\Track;
use App\Notifications\BaseNotification;
use App\User;
use Illuminate\Auth\Events\Registered;

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
        $events->listen('eloquent.created: '.Track::class, self::class.'@uploadFirstTrack');
        $events->listen('eloquent.created: '.Album::class, self::class.'@createFirstAlbum');
        $events->listen('eloquent.created: '.Collection::class, self::class.'@createFirstCollection');
        $events->listen('eloquent.created: '.Favourite::class, self::class.'@favourite');
        $events->listen('eloquent.created: '.Comment::class, self::class.'@criticReview');
        $events->listen(Registered::class, self::class.'@registerUser');
        $events->listen(EntranceInAppEvent::class, self::class.'@entranceInApp');
        $events->listen(ListeningTenTrackEvent::class, self::class.'@listeningTenTrack');
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
        $favoriteType = 'favouriteable_type';
        $favoriteId = 'favouriteable_id';
        /** @var User | null $notifyUser */
        $notifyUser = null;

        switch (get_class($favourite->favouriteable()->getRelated())) {
            case Album::class:

                break;
            case Track::class:
                break;
            case Collection::class:
                break;
            default:
                return;
        }

        if (null !== $notifyUser) {
            $baseNotifyMessage = new BaseNotifyMessage('rating');
            $notifyUser->notify(new BaseNotification($baseNotifyMessage));
        }
    }
}
