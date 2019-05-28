<?php

namespace App\Listeners;

use App\Events\EntranceInAppEvent;
use App\Events\ListeningTenTrackEvent;
use App\Interfaces\BonusProgramTypesInterfaces;
use App\Models\Album;
use App\Models\BonusType;
use App\Models\Collection;
use App\Models\Comment;
use App\Models\Favourite;
use App\Models\Operation;
use App\Models\Purse;
use App\Models\Track;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Carbon;

class BonusProgramEventSubscriber
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
     * @param Track $track
     */
    public function uploadFirstTrack(Track $track)
    {
        /** @var User $user */
        $user = $track->user;
        //todo  cache for find
        $bonusType = BonusType::query()->where('constant_name', '=', BonusProgramTypesInterfaces::UPLOAD_FIRST_TRACK)->first();
        if (null === $bonusType) {
            return;
        }
        $purse = $user->purse()->firstOrNew(['user_id' => $user->id, 'name' => Purse::NAME_BONUS]);
        $countOperation = Operation::query()->where('purse_id', '=', $purse->id)->where('type_id', '=', $bonusType->id)->count();
        if (0 === $countOperation) {
            $operation = new Operation([
                'direction' => Operation::DIRECTION_INCREASE,
                'amount' => $bonusType->bonus,
                'description' => $bonusType->name,
                'type_id' => $bonusType->id,
            ]);
            $user->purseBonus->processOperation($operation);
        }
    }

    /**
     * @param Album $track
     */
    public function createFirstAlbum(Album $track)
    {
        /** @var User $user */
        $user = $track->user;
        //todo  cache for find
        $bonusType = BonusType::query()->where('constant_name', '=', BonusProgramTypesInterfaces::CREATE_FIRST_ALBUM)->first();
        if (null === $bonusType) {
            return;
        }
        $purse = $user->purse()->firstOrNew(['user_id' => $user->id, 'name' => Purse::NAME_BONUS]);
        $countOperation = Operation::query()->where('purse_id', '=', $purse->id)->where('type_id', '=', $bonusType->id)->count();
        if (0 === $countOperation) {
            $operation = new Operation([
                'direction' => Operation::DIRECTION_INCREASE,
                'amount' => $bonusType->bonus,
                'description' => $bonusType->name,
                'type_id' => $bonusType->id,
            ]);
            $user->purseBonus->processOperation($operation);
        }
    }

    /**
     * @param Collection $track
     */
    public function createFirstCollection(Collection $track)
    {
        /** @var User $user */
        $user = $track->user;
        //todo  cache for find
        $bonusType = BonusType::query()->where('constant_name', '=', BonusProgramTypesInterfaces::CREATE_FIRST_PLAYLIST)->first();
        if (null === $bonusType) {
            return;
        }
        $purse = $user->purse()->firstOrNew(['user_id' => $user->id, 'name' => Purse::NAME_BONUS]);
        $countOperation = Operation::query()->where('purse_id', '=', $purse->id)->where('type_id', '=', $bonusType->id)->count();
        if (0 === $countOperation) {
            $operation = new Operation([
                'direction' => Operation::DIRECTION_INCREASE,
                'amount' => $bonusType->bonus,
                'description' => $bonusType->name,
                'type_id' => $bonusType->id,
            ]);
            $user->purseBonus->processOperation($operation);
        }
    }

    /**
     * Получение 10 лайков от других пользователей на трек
     * Получение 10 лайков от других пользователей на альбом
     * Популярный плейлист
     *
     * @param Favourite $favourite
     */
    public function favourite(Favourite $favourite)
    {
        /** @var User $user */
        $user = $favourite->user;
        $favoriteType = 'favouriteable_type';
        $favoriteId = 'favouriteable_id';

        switch (get_class($favourite->favouriteable()->getRelated())) {
            case Album::class:
                $modelId = $favourite->album->id;
                $countFavorite =
                    Favourite::query()
                        ->where($favoriteType, '=', get_class($favourite->favouriteable()->getRelated()))
                        ->where($favoriteId, $modelId)
                        ->count()
                ;
                if ($countFavorite < 10) {
                    return;
                }

                $bonusTypeConstant = BonusProgramTypesInterfaces::GETTING_TEN_LIKES_FROM_OTHER_USERS_PER_ALBUM;
                break;
            case Track::class:
                $modelId = $favourite->track->id;
                $countFavorite =
                    Favourite::query()
                        ->where($favoriteType, '=', get_class($favourite->favouriteable()->getRelated()))
                        ->where($favoriteId, $modelId)
                        ->count()
                ;
                if ($countFavorite < 10) {
                    return;
                }
                $bonusTypeConstant = BonusProgramTypesInterfaces::GETTING_TEN_LIKES_FROM_OTHER_USERS_PER_TRACK;
                break;
            case Collection::class:
                $modelId = $favourite->track->id;
                $countFavorite =
                    Favourite::query()
                        ->where($favoriteType, '=', get_class($favourite->favouriteable()->getRelated()))
                        ->where($favoriteId, $modelId)
                        ->count()
                ;
                if ($countFavorite < 50) {
                    return;
                }
                $bonusTypeConstant = BonusProgramTypesInterfaces::GETTING_TEN_LIKES_FROM_OTHER_USERS_PER_TRACK;
                break;
                break;
            default:
                return;
        }

        $bonusType = BonusType::query()
            ->where('constant_name', '=', $bonusTypeConstant)
            ->first();

        if (null === $bonusType) {
            return;
        }

        $purse = $user->purse()->firstOrNew(['user_id' => $user->id, 'name' => Purse::NAME_BONUS]);
        $countOperation = Operation::query()
            ->where('purse_id', '=', $purse->id)
            ->where('type_id', '=', $bonusType->id)
            ->where('extra_data', '=', $modelId)
            ->count()
        ;

        if ($countOperation > 0) {
            return;
        }

        $operation = new Operation([
            'direction' => Operation::DIRECTION_INCREASE,
            'amount' => $bonusType->bonus,
            'description' => $bonusType->name,
            'type_id' => $bonusType->id,
            'extra_data' => $modelId,
        ]);
        $user->purseBonus->processOperation($operation);
    }

    /**
     * Рецензия от критика.
     *
     * @param Comment $comment
     */
    public function criticReview(Comment $comment)
    {
        $bonusType = BonusType::query()
            ->where('constant_name', '=', BonusProgramTypesInterfaces::CRITIC_REVIEW)
            ->first();

        if (null === $bonusType) {
            return;
        }
        /** @var User $user */
        $user = $comment->user;
        $operation = new Operation([
            'direction' => Operation::DIRECTION_INCREASE,
            'amount' => $bonusType->bonus,
            'description' => $bonusType->name,
            'type_id' => $bonusType->id,
        ]);
        $user->purseBonus->processOperation($operation);
    }

    public function registerUser(User $user)
    {
        $bonusType = BonusType::query()
            ->where('constant_name', '=', BonusProgramTypesInterfaces::USER_REGISTER)
            ->first();

        if (null === $bonusType) {
            return;
        }
        $operation = new Operation([
            'direction' => Operation::DIRECTION_INCREASE,
            'amount' => $bonusType->bonus,
            'description' => $bonusType->name,
            'type_id' => $bonusType->id,
        ]);
        $user->purseBonus->processOperation($operation);
    }

    /**
     * Ежедневный вход в приложение.
     *
     * @param User $user
     */
    public function entranceInApp(User $user)
    {
        $bonusType = BonusType::query()
            ->where('constant_name', '=', BonusProgramTypesInterfaces::DAILY_ENTRANCE_TO_THE_APP)
            ->first();

        if (null === $bonusType) {
            return;
        }

        $dt = Carbon::now();
        $diffDate = $dt->diffInDays($user->last_entrance_app);
        if ($diffDate <= 0) {
            return;
        }
        if ($diffDate > 1) {
            $user->last_entrance_app = Carbon::now();
            $user->count_entrance_app = 0;
            $user->save();

            return;
        }

        $user->last_entrance_app = Carbon::now();
        ++$user->count_entrance_app;
        $user->save();

        // Первый день
        if (1 === $user->count_entrance_app) {
            return;
        }

        switch ($user->count_entrance_app) {
            case 2:
                $bonus = 3;
                break;
            case 3:
                $bonus = 6;
                break;
            case 4:
                $bonus = 12;
                break;
            case 5:
                $bonus = 20;
                break;
            case 6:
                $bonus = 35;
                break;
            default:
                $bonus = 50;
        }

        $operation = new Operation([
            'direction' => Operation::DIRECTION_INCREASE,
            'amount' => $bonus,
            'description' => $bonusType->name,
            'type_id' => $bonusType->id,
        ]);

        $user->purseBonus->processOperation($operation);
    }

    public function listeningTenTrack(User $user)
    {
        $bonusType = BonusType::query()
            ->where('constant_name', '=', BonusProgramTypesInterfaces::LISTENING_TEN_TRACKS)
            ->first();

        if (null === $bonusType) {
            return;
        }

        /** @var User $user */
        $operation = new Operation([
            'direction' => Operation::DIRECTION_INCREASE,
            'amount' => $bonusType->bonus,
            'description' => $bonusType->name,
            'type_id' => $bonusType->id,
        ]);
        $user->purseBonus->processOperation($operation);
    }
}