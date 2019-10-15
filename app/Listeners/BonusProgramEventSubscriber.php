<?php

namespace App\Listeners;

use App\BuisnessLogic\BonusProgram\UserLevels;
use App\Events\CompletedTaskEvent;
use App\Events\EntranceInAppEvent;
use App\Events\ListeningTenTrackEvent;
use App\Events\Track\TrackPublishEvent;
use App\Interfaces\BonusProgramTypesInterfaces;
use App\Models\Album;
use App\Models\ArtistProfile;
use App\Models\BonusType;
use App\Models\Collection;
use App\Models\Comment;
use App\Models\Favourite;
use App\Models\Operation;
use App\Models\Purse;
use App\Models\Social;
use App\Models\Track;
use App\Models\Watcheables;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

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
        $events->listen(TrackPublishEvent::class, self::class.'@uploadFirstTrack');
        $events->listen('eloquent.created: '.Album::class, self::class.'@createFirstAlbum');
        $events->listen('eloquent.created: '.Collection::class, self::class.'@createFirstCollection');
        $events->listen('eloquent.created: '.Favourite::class, self::class.'@favourite');
        $events->listen('eloquent.created: '.Comment::class, self::class.'@criticReview');
        $events->listen(Registered::class, self::class.'@registerUser');
        $events->listen(EntranceInAppEvent::class, self::class.'@entranceInApp');
        $events->listen(ListeningTenTrackEvent::class, self::class.'@listeningTenTrack');
        $events->listen('eloquent.updated: '.User::class, self::class.'@uploadAvatar');
        $events->listen('eloquent.updated: '.User::class, self::class.'@fillUsername');
        $events->listen('eloquent.updated: '.User::class, self::class.'@fillCity');
        $events->listen('eloquent.updated: '.ArtistProfile::class, self::class.'@fillCareerStart');
        $events->listen('eloquent.updated: '.ArtistProfile::class, self::class.'@fillArtistDescription');
        $events->listen('eloquent.saved: '.ArtistProfile::class, self::class.'@fillArtistGenres');
        $events->listen('eloquent.updated: '.Social::class, self::class.'@fillSocials');
        $events->listen(CompletedTaskEvent::class, self::class.'@changeLevel');
        $events->listen('eloquent.created: '.Watcheables::class, self::class.'@createWatcheables');
    }

    /**
     * @param TrackPublishEvent $trackPublishEvent
     */
    public function uploadFirstTrack(TrackPublishEvent $trackPublishEvent)
    {
        /** @var User $user */
        $user = $trackPublishEvent->getTrack()->user;
        if ($this->participatesInBonusProgram($user)) {
            //todo  cache for find
            $bonusType = BonusType::query()->where('constant_name', '=', BonusProgramTypesInterfaces::UPLOAD_FIRST_TRACK)->first();
            if (null === $bonusType) {
                return;
            }
            if (null === $user->purse) {
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
                event(new CompletedTaskEvent($user, $bonusType->description, $bonusType->bonus));
            }
        }
    }

    /**
     * @param Album $track
     */
    public function createFirstAlbum(Album $track)
    {
        /** @var User $user */
        $user = $track->user;
        if ($this->participatesInBonusProgram($user)) {
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
                event(new CompletedTaskEvent($user, $bonusType->description, $bonusType->bonus));
            }
        }
    }

    /**
     * @param Collection $track
     */
    public function createFirstCollection(Collection $track)
    {
        /** @var User $user */
        $user = $track->user;
        if ($this->participatesInBonusProgram($user)) {
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
                event(new CompletedTaskEvent($user, $bonusType->description, $bonusType->bonus));
            }
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
        if ($this->participatesInBonusProgram($user)) {
            $favoriteType = 'favouriteable_type';
            $favoriteId = 'favouriteable_id';

            switch (get_class($favourite->favouriteable()->getRelated())) {
                case Album::class:
                    $modelId = $favourite->album->id;
                    $albumUser = $favourite->album->user;
                    if ($user->id === $albumUser->id) {
                        return;
                    }
                    $countFavorite =
                        Favourite::query()
                            ->where($favoriteType, '=', get_class($favourite->favouriteable()->getRelated()))
                            ->where($favoriteId, $modelId)
                            ->where('user_id', '<>', $user->id)
                            ->count();

                    if ($countFavorite < 10) {
                        return;
                    }

                    $bonusTypeConstant = BonusProgramTypesInterfaces::GETTING_TEN_LIKES_FROM_OTHER_USERS_PER_ALBUM;
                    break;
                case Track::class:
                    $modelId = $favourite->track->id;
                    $trackUser = $favourite->track->user;
                    if ($user->id === $trackUser->id) {
                        return;
                    }
                    $countFavorite =
                        Favourite::query()
                            ->where($favoriteType, '=', get_class($favourite->favouriteable()->getRelated()))
                            ->where($favoriteId, $modelId)
                            ->where('user_id', '<>', $user->id)
                            ->count();
                    if ($countFavorite < 10) {
                        return;
                    }
                    $bonusTypeConstant = BonusProgramTypesInterfaces::GETTING_TEN_LIKES_FROM_OTHER_USERS_PER_TRACK;
                    break;
//            case Collection::class:
//                $modelId = $favourite->collection->id;
//                $countFavorite =
//                    Favourite::query()
//                        ->where($favoriteType, '=', get_class($favourite->favouriteable()->getRelated()))
//                        ->where($favoriteId, $modelId)
//                        ->count()
//                ;
//                if ($countFavorite < 50) {
//                    return;
//                }
//                $bonusTypeConstant = BonusProgramTypesInterfaces::GETTING_TEN_LIKES_FROM_OTHER_USERS_PER;
//                break;
//                break;
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
                ->count();

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
            event(new CompletedTaskEvent($user, $bonusType->description, $bonusType->bonus));
        }
    }

    /**
     * Рецензия от критика.
     *
     * @param Comment $comment
     */
    public function criticReview(Comment $comment)
    {
        /** @var User $user */
        $user = $comment->user;
        if ($this->participatesInBonusProgram($user)) {
            $bonusType = BonusType::query()
                ->where('constant_name', '=', BonusProgramTypesInterfaces::CRITIC_REVIEW)
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
            event(new CompletedTaskEvent($user, $bonusType->description, $bonusType->bonus));
        }
    }

    public function registerUser(Registered $registered)
    {
        $user = $registered->user;
        if ($this->participatesInBonusProgram($user)) {
            $bonusType = BonusType::query()
                ->where('constant_name', '=', BonusProgramTypesInterfaces::USER_REGISTER)
                ->first();

            if (null === $bonusType) {
                return;
            }
            $user->save();
            $purse = $user->purseBonus;
            if (null === $purse) {
                $purse = new Purse();
                $purse->balance = 0;
                $purse->name = Purse::NAME_BONUS;
                $purse->user_id = $user->id;
                $user->purse()->save($purse);
                $purse->save();
            }

            $operation = new Operation([
                'direction' => Operation::DIRECTION_INCREASE,
                'amount' => $bonusType->bonus,
                'description' => $bonusType->name,
                'type_id' => $bonusType->id,
            ]);
            $purse->processOperation($operation);
            event(new CompletedTaskEvent($user, $bonusType->description, $bonusType->bonus));
        }
    }

    /**
     * Ежедневный вход в приложение.
     *
     * @param User $user
     */
    public function entranceInApp(User $user)
    {
        if ($this->participatesInBonusProgram($user)) {
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
            event(new CompletedTaskEvent($user, $bonusType->description, $bonusType->bonus));
        }
    }

    public function listeningTenTrack(ListeningTenTrackEvent $listeningTenTrackEvent)
    {
        $user = $listeningTenTrackEvent->getUser();
        if (null !== $user && $this->participatesInBonusProgram($user)) {
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
            event(new CompletedTaskEvent($user, $bonusType->description, $bonusType->bonus));
        }
    }

    /**
     * Загрузка аватарки.
     *
     * @param User $user
     */
    public function uploadAvatar(User $user)
    {
        /** @var User $user */
        if (null !== $user && $this->participatesInBonusProgram($user) && false !== $user->isDirty('avatar')) {
            $this->accureBonus(BonusProgramTypesInterfaces::AVATAR, $user);
        }
    }

    /**
     * Заполнение имени пользователя.
     *
     * @param User $user
     *
     * @return bool|void
     */
    public function fillUsername(User $user)
    {
        /** @var User $user */
        if (null !== $user && $this->participatesInBonusProgram($user) && false !== $user->isDirty('username')) {
            $this->accureBonus(BonusProgramTypesInterfaces::USER_NAME, $user);
        }
    }

    /**
     * Заполнение Города.
     *
     * @param User $user
     *
     * @return bool|void
     */
    public function fillCity(User $user)
    {
        /** @var User $user */
        if (null !== $user && $this->participatesInBonusProgram($user) && false !== $user->isDirty('city_id')) {
            $this->accureBonus(BonusProgramTypesInterfaces::CITY, $user);
        }
    }

    /**
     * Заполнение Города.
     *
     * @param User $user
     *
     * @return bool|void
     */
    public function fillCareerStart(ArtistProfile $artist)
    {
        /** @var User $user */
        $user = Auth::user();
        if (null !== $user && $this->participatesInBonusProgram($user) && false !== $artist->isDirty('career_start')) {
            $this->accureBonus(BonusProgramTypesInterfaces::CAREER_START, $user);
        }
    }

    /**
     * Заполнение описания.
     *
     * @param User $user
     *
     * @return bool|void
     */
    public function fillArtistDescription(ArtistProfile $artist)
    {
        /** @var User $user */
        $user = Auth::user();
        if (null !== $user && $this->participatesInBonusProgram($user) && false !== $artist->isDirty('description')) {
            $this->accureBonus(BonusProgramTypesInterfaces::ARTIST_DESCRIPTION, $user);
        }
    }

    /**
     * Заполнение жанров в которых играет.
     *
     * @param User $user
     *
     * @return bool|void
     */
    public function fillArtistGenres(ArtistProfile $artist)
    {
        /** @var User $user */
        $user = Auth::user();
        if (null !== $user && $this->participatesInBonusProgram($user) && 0 !== $artist->genres->count()) {
            $this->accureBonus(BonusProgramTypesInterfaces::ARTIST_GENRES, $user);
        }
    }

    /**
     * Заполнение соц. сети.
     *
     * @param User $user
     *
     * @return bool|void
     */
    public function fillSocials(Social $social)
    {
        /** @var User $user */
        $user = $social->user;
        if (null !== $user && $this->participatesInBonusProgram($user)) {
            $this->accureBonus(BonusProgramTypesInterfaces::SOCIALS, $user);
        }
    }

    public function changeLevel(CompletedTaskEvent $event)
    {
        /** @var User $user */
        $user = $event->getUser();
        $userLevel = new UserLevels();
        $userLevel->changeUserLevel($user);
    }

    /**
     * начисление бонуса.
     *
     * @param $bonusName
     * @param User $user
     */
    public function accureBonus($bonusName, User $user)
    {
        $bonusType = Cache::get(BonusType::BONUS_TYPE.'_'.$bonusName);
        if (null === $bonusType) {
            $bonusType = BonusType::query()->where('constant_name', '=', $bonusName)->first();
            Cache::put(BonusType::BONUS_TYPE.'_'.$bonusName, $bonusType, 60);
        }

        if (null !== $bonusType) {
            $purse = $user->purseBonus;
            if (null === $purse) {//при регистрации через соцсети может не быть кошелька
                $purse = $user->purseBonus()->firstOrCreate(['user_id' => $user->id, 'name' => Purse::NAME_BONUS]);
            }
            $countOperation = Operation::countOperation($bonusType, $user);
            if (0 === $countOperation) {
                $operation = new Operation([
                    'direction' => Operation::DIRECTION_INCREASE,
                    'amount' => $bonusType->bonus,
                    'description' => $bonusType->name,
                    'type_id' => $bonusType->id,
                ]);
                $purse->processOperation($operation);
                event(new CompletedTaskEvent($user, $bonusType->description, $bonusType->bonus));
            }
        }
    }

    /**
     * участвует ли пользователь в бонусной программе
     * исключаем пользователей с определенными ролями из бонусной программы.
     *
     * @param User $user
     *
     * @return bool
     */
    private function participatesInBonusProgram(User $user): bool
    {
        /** @var User $user */
        if (true === $user->inRoles(['prof_critic', 'star'])) {
            return false;
        }

        return true;
    }

    public function createWatcheables(Watcheables $watcheables): void
    {
        /** @var User $user */
        $user = $watcheables->user()->first();

        if ($this->participatesInBonusProgram($user)) {
            $bonusType = BonusType::query()->where('constant_name', '=', BonusProgramTypesInterfaces::RECEIVING_POINTS_FIRST_FOLLOWER)->first();
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
                event(new CompletedTaskEvent($user, $bonusType->description, $bonusType->bonus));
            }
        }
    }
}
