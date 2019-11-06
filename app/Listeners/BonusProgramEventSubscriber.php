<?php

namespace App\Listeners;

use App\BuisnessLogic\BonusProgram\UserLevels;
use App\Events\CompletedTaskEvent;
use App\Events\CreatedTopFiftyEvent;
use App\Events\EntranceInAppEvent;
use App\Events\ListeningTenTrackEvent;
use App\Events\Track\EntryTrackInTopFiftyEvent;
use App\Events\Track\TrackPublishEvent;
use App\Events\User\CreatedTopFiveTrack;
use App\Events\User\TopFiveMonthEvent;
use App\Events\User\TopFiveWeekEvent;
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
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BonusProgramEventSubscriber
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
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
        $events->listen(CreatedTopFiftyEvent::class, self::class.'@createdTopFifty'); // Попадание в плейлист ТОП-50 (обновление ежедневно)
        $events->listen(EntryTrackInTopFiftyEvent::class, self::class.'@entryTrackInTopFifty'); // Попадание в плейлист ТОП-50 (обновление ежедневно)
        $events->listen(CreatedTopFiveTrack::class, self::class.'@calculateTopFiveUser');
        $events->listen(TopFiveWeekEvent::class, self::class.'@topFiveWeekUser');
        $events->listen(TopFiveMonthEvent::class, self::class.'@topFiveMonthUser');
    }

    /**
     * @param TrackPublishEvent $trackPublishEvent
     *
     * @throws Exception
     */
    public function uploadFirstTrack(TrackPublishEvent $trackPublishEvent): void
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
     *
     * @throws Exception
     */
    public function createFirstAlbum(Album $track): void
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
     *
     * @throws Exception
     */
    public function createFirstCollection(Collection $track): void
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
     *
     * @throws Exception
     */
    public function favourite(Favourite $favourite): void
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
            case Collection::class:
                $modelId = $favourite->collection->id;
                $countFavorite =
                    Favourite::query()
                        ->where($favoriteType, '=', get_class($favourite->favouriteable()->getRelated()))
                        ->where($favoriteId, $modelId)
                        ->count()
                ;
                if ($countFavorite < 50) {
                    return;
                }
                $bonusTypeConstant = BonusProgramTypesInterfaces::POPULATE_PLAY_LIST;
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
     *
     * @throws Exception
     */
    public function criticReview(Comment $comment): void
    {
        /** @var User $user */
        $user = $comment->user;
        if ($this->participatesInBonusProgram($user)) {
            $this->accrueBonus(BonusProgramTypesInterfaces::CRITIC_REVIEW, $user);
        }
    }

    public function registerUser(Registered $registered): void
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
     *
     * @throws Exception
     */
    public function entranceInApp(User $user): void
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

    public function listeningTenTrack(ListeningTenTrackEvent $listeningTenTrackEvent): void
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
     *
     * @throws Exception
     */
    public function uploadAvatar(User $user): void
    {
        /** @var User $user */
        if (null !== $user && $this->participatesInBonusProgram($user) && false !== $user->isDirty('avatar')) {
            $this->accrueBonus(BonusProgramTypesInterfaces::AVATAR, $user);
        }
    }

    /**
     * Заполнение имени пользователя.
     *
     * @param User $user
     *
     * @return bool|void
     *
     * @throws Exception
     */
    public function fillUsername(User $user)
    {
        /** @var User $user */
        if (null !== $user && $this->participatesInBonusProgram($user) && false !== $user->isDirty('username')) {
            $this->accrueBonus(BonusProgramTypesInterfaces::USER_NAME, $user);
        }
    }

    /**
     * Заполнение Города.
     *
     * @param User $user
     *
     * @return bool|void
     *
     * @throws Exception
     */
    public function fillCity(User $user)
    {
        /** @var User $user */
        if (null !== $user && $this->participatesInBonusProgram($user) && false !== $user->isDirty('city_id')) {
            $this->accrueBonus(BonusProgramTypesInterfaces::CITY, $user);
        }
    }

    /**
     * Заполнение Города.
     *
     * @param ArtistProfile $artist
     *
     * @return bool|void
     *
     * @throws Exception
     */
    public function fillCareerStart(ArtistProfile $artist)
    {
        /** @var User $user */
        $user = $artist->user;
        if (null !== $user && $this->participatesInBonusProgram($user) && false !== $artist->isDirty('career_start')) {
            $this->accrueBonus(BonusProgramTypesInterfaces::CAREER_START, $user);
        }
    }

    /**
     * Заполнение описания.
     *
     * @param ArtistProfile $artist
     *
     * @return bool|void
     *
     * @throws Exception
     */
    public function fillArtistDescription(ArtistProfile $artist)
    {
        /** @var User $user */
        $user = $artist->user;
        if (null !== $user && $this->participatesInBonusProgram($user) && false !== $artist->isDirty('description')) {
            $this->accrueBonus(BonusProgramTypesInterfaces::ARTIST_DESCRIPTION, $user);
        }
    }

    /**
     * Заполнение жанров в которых играет.
     *
     * @param ArtistProfile $artist
     *
     * @return bool|void
     *
     * @throws Exception
     */
    public function fillArtistGenres(ArtistProfile $artist)
    {
        /** @var User $user */
        $user = $artist->user;
        if (null !== $user && $this->participatesInBonusProgram($user) && 0 !== $artist->genres->count()) {
            $this->accrueBonus(BonusProgramTypesInterfaces::ARTIST_GENRES, $user);
        }
    }

    /**
     * Заполнение соц. сети.
     *
     * @param Social $social
     *
     * @return bool|void
     *
     * @throws Exception
     */
    public function fillSocials(Social $social)
    {
        /** @var User $user */
        $user = $social->user;
        if (null !== $user && $this->participatesInBonusProgram($user)) {
            $this->accrueBonus(BonusProgramTypesInterfaces::SOCIALS, $user);
        }
    }

    public function changeLevel(CompletedTaskEvent $event): void
    {
        /** @var User $user */
        $user = $event->getUser();
        $userLevel = new UserLevels();
        $userLevel->changeUserLevel($user);
    }

    /**
     * начисление бонуса.
     *
     * @param string $bonusName
     * @param User   $user       Пользователь
     * @param int    $operations кол-во выполнений если 1 то только 1 раз зачисляется если -1 то при каждом выполнении задния
     * @param int    $bonusBall  начисляемые баллы
     * @param mixed  $extraData
     *
     * @throws Exception
     */
    public function accrueBonus(string $bonusName, User $user, int $operations = 1, int $bonusBall = null, $extraData = null): void
    {
        $bonusType = Cache::get(BonusType::BONUS_TYPE.'_'.$bonusName);
        if (null === $bonusType) {
            $bonusType = BonusType::query()->where('constant_name', '=', $bonusName)->first();
            Cache::put(BonusType::BONUS_TYPE.'_'.$bonusName, $bonusType, 60);
        }

        if (null !== $bonusType) {
            $purse = $user->purseBonus;
            if (null === $purse) { //при регистрации через соцсети может не быть кошелька
                $purse = $user->purseBonus()->firstOrCreate(['user_id' => $user->id, 'name' => Purse::NAME_BONUS]);
            }
            $countOperation = Operation::countOperation($bonusType, $user, $extraData);
            $bonus = $bonusBall ?? $bonusType->bonus;

            if ($operations === $countOperation && -1 != $operations) {
                return;
            }
            $operation = new Operation([
                'direction' => Operation::DIRECTION_INCREASE,
                'amount' => $bonus,
                'description' => $bonusType->name,
                'type_id' => $bonusType->id,
                'extra_data' => $extraData,
            ]);
            $purse->processOperation($operation);
            event(new CompletedTaskEvent($user, $bonusType->description, $bonus));
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
        /* @var User $user */
        return !(true === $user->inRoles(['prof_critic', 'star']));
    }

    public function createWatcheables(Watcheables $watcheables): void
    {
        /** @var User $user */
        $user = $watcheables->user;
        if (null === $user) {
            return;
        }
        if (false === $this->participatesInBonusProgram($user)) {
            return;
        }
        $bonusType = BonusType::query()->where('constant_name', '=', BonusProgramTypesInterfaces::RECEIVING_POINTS_FOR_SUBSCRIBERS)->first();
        if (null === $bonusType) {
            return;
        }

        $count = $user->watchingUser()->count();
        $extraData = null;
        switch ($count) {
            case 1:
                $bonus = 30;
                $extraData = 1;
                break;
            case 10:
                $bonus = 50;
                $extraData = 10;
                break;
            case 50:
                $bonus = 120;
                $extraData = 50;
                break;
            case 100:
                $bonus = 150;
                $extraData = 100;
                break;
            case 500:
                $bonus = 1200;
                $extraData = 500;
                break;
            case 1000:
                $bonus = 1500;
                $extraData = 1000;
                break;
            case 5000:
                $bonus = 12000;
                $extraData = 5000;
                break;
            default:
                $bonus = 0;
        }

        if (0 === $bonus) {
            return;
        }

        try {
            $this->accrueBonus(BonusProgramTypesInterfaces::RECEIVING_POINTS_FOR_SUBSCRIBERS, $user, 1, $bonus, $extraData);
        } catch (Exception $e) {
            Log::alert($e->getMessage());
        }
    }

    public function createdTopFifty(CreatedTopFiftyEvent $createdTopFiftyEvent): void
    {
        $trackIdsThree = array_slice($createdTopFiftyEvent->getIdsTrack(), 0, 3);

        $trackIdsFive = array_slice($createdTopFiftyEvent->getIdsTrack(), 0, 5);
        event(new CreatedTopFiveTrack($trackIdsFive));

        $tracks = Track::query()->findMany($trackIdsThree)->all();

        foreach ($tracks as $place => $track) {
            event(new EntryTrackInTopFiftyEvent($track, $place));
        }
    }

    public function entryTrackInTopFifty(EntryTrackInTopFiftyEvent $entryTrackInTopFifty): void
    {
        $track = $entryTrackInTopFifty->getTrack();
        $user = $track->user;
        if (false === $this->participatesInBonusProgram($user)) {
            return;
        }

        $place = $entryTrackInTopFifty->getPlace();

        $bonusType = BonusType::query()->where('constant_name', '=', BonusProgramTypesInterfaces::ENTRY_IN_PLAYLIST_TOP_FIFTY)->first();
        if (null === $bonusType) {
            return;
        }
        /** @var array $topFiftyPlacesConfig */
        $topFiftyPlacesConfig = config('topFiftyPlaces');
        $bonus = $topFiftyPlacesConfig($place);

        if (null === $bonus) {
            return;
        }

        try {
            $this->accrueBonus(BonusProgramTypesInterfaces::ENTRY_IN_PLAYLIST_TOP_FIFTY, $user, $bonus);
        } catch (Exception $e) {
            Log::alert($e->getMessage());
        }
    }

    public function calculateTopFiveUser(CreatedTopFiveTrack $createdTopFiveTrack): void
    {
        $users = User::query()
            ->select('users.id')
            ->leftJoin('tracks', 'users.id', '=', 'tracks.user_id')
            ->whereIn('tracks.id', $createdTopFiveTrack->getTracksId())
            ->distinct()
            ->get()->pluck('id')->toArray()
        ;
        $keyCacheTopFive = 'ids_users_in_top_five';
        $userInCache = Cache::get($keyCacheTopFive, []);
        foreach ($userInCache  as $userId => $date) {
            $key = array_search($userId, $users);
            if (false === $key) {
                unset($userInCache[$userId]);
                continue;
            }

            unset($users[$key]);

            $diffInDays = Carbon::now()->diffInDays($date);
            $checkDateWeek = $diffInDays % 7;
            $checkDateMonth = $diffInDays % 30;
            if ($diffInDays >= 7 && 0 === $checkDateWeek) {
                event(new TopFiveWeekEvent($userId));
            }
            if ($diffInDays >= 30 && 0 === $checkDateMonth) {
                event(new TopFiveMonthEvent($userId));
            }
        }

        foreach ($users as $userId) {
            $userInCache[$userId] = new \DateTime();
        }

        Cache::forever($keyCacheTopFive, (array) $userInCache);
    }

    public function topFiveWeekUser(TopFiveWeekEvent $fiveWeekEvent): void
    {
        $user = User::find($fiveWeekEvent->getIdUser());
        if (false === $this->participatesInBonusProgram($user)) {
            return;
        }
        try {
            $this->accrueBonus(BonusProgramTypesInterfaces::CONTINUOUS_PRESENCE_IN_THE_TOP_FIVE_WEEK, $user, -1);
        } catch (Exception $e) {
            Log::alert($e->getMessage());
        }
    }

    public function topFiveMonthUser(TopFiveMonthEvent $fiveWeekEvent): void
    {
        $user = User::find($fiveWeekEvent->getIdUser());
        if (false === $this->participatesInBonusProgram($user)) {
            return;
        }
        try {
            $this->accrueBonus(BonusProgramTypesInterfaces::CONTINUOUS_PRESENCE_IN_THE_TOP_FIVE_MONTH, $user, -1);
        } catch (Exception $e) {
            Log::alert($e->getMessage());
        }
    }
}
