<?php

namespace App\Listeners;

use App\BonusProgram\AccrueBonusService;
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
use App\Models\Collection;
use App\Models\Comment;
use App\Models\Favourite;
use App\Models\Social;
use App\Models\Track;
use App\Models\Watcheables;
use App\Repositories\FavoriteRepositories;
use App\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class BonusProgramEventSubscriber
{
    private $accrueBonusService;
    private $favoriteRepositories;

    public function __construct(AccrueBonusService $accrueBonusService, FavoriteRepositories $favoriteRepositories)
    {
        $this->accrueBonusService = $accrueBonusService;
        $this->favoriteRepositories = $favoriteRepositories;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     */
    public function subscribe($events): void
    {
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
        $user = $trackPublishEvent->getTrack()->user;
        $this->accrueBonusService->process(BonusProgramTypesInterfaces::UPLOAD_FIRST_TRACK, $user);
    }

    /**
     * @param Album $track
     *
     * @throws Exception
     */
    public function createFirstAlbum(Album $track): void
    {
        $user = $track->user;
        $this->accrueBonusService->process(BonusProgramTypesInterfaces::CREATE_FIRST_ALBUM, $user);
    }

    /**
     * @param Collection $track
     *
     * @throws Exception
     */
    public function createFirstCollection(Collection $track): void
    {
        $user = $track->user;
        $this->accrueBonusService->process(BonusProgramTypesInterfaces::CREATE_FIRST_PLAYLIST, $user);
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

        $classFavourite = get_class($favourite->favouriteable()->getRelated());
        switch ($classFavourite) {
                case Album::class:
                    $modelId = $favourite->album->id;
                    $albumUser = $favourite->album->user;
                    if ($user->id === $albumUser->id) {
                        return;
                    }
                    $countFavorite = $this->favoriteRepositories->countFavorite($favourite, $modelId, $user);

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
                    $countFavorite = $this->favoriteRepositories->countFavorite($favourite, $modelId, $user);
                    if ($countFavorite < 10) {
                        return;
                    }
                    $bonusTypeConstant = BonusProgramTypesInterfaces::GETTING_TEN_LIKES_FROM_OTHER_USERS_PER_TRACK;
                    break;
                case Collection::class:
                    $modelId = $favourite->collection->id;
                    $countFavorite = $this->favoriteRepositories->countFavorite($favourite, $modelId, $user);
                    if ($countFavorite < 50) {
                        return;
                    }
                    $bonusTypeConstant = BonusProgramTypesInterfaces::POPULATE_PLAY_LIST;
                    break;
                default:
                    return;
        }

        $this->accrueBonusService->process($bonusTypeConstant, $user, 1, null, $modelId);
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
        $user = $comment->user;
        $this->accrueBonusService->process(BonusProgramTypesInterfaces::CRITIC_REVIEW, $user, -1);
    }

    public function registerUser(Registered $registered): void
    {
        $user = User::query()->find($registered->user->getAuthIdentifier());
        $this->accrueBonusService->process(BonusProgramTypesInterfaces::USER_REGISTER, $user); //todo
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
        $dt = Carbon::now();
        $diffDate = $dt->diffInDays($user->last_entrance_app);
        if ($diffDate <= 0) {
            return;
        }
        $countEntranceApp = $user->count_entrance_app;
        if ($diffDate > 1) {
            $user->last_entrance_app = Carbon::now();
            $user->save();

            return;
        }

        $user->last_entrance_app = Carbon::now();
        ++$countEntranceApp;
        $user->save();

        // Первый день
        if (1 === $countEntranceApp) {
            return;
        }

        if ($countEntranceApp >= 7) {
            $bonus = 50;
        } else {
            $config = config('bonus.entranceApp');
            $bonus = $config[$countEntranceApp] ?? null;
        }

        if (null === $bonus) {
            return;
        }

        $this->accrueBonusService->process(BonusProgramTypesInterfaces::DAILY_ENTRANCE_TO_THE_APP, $user, -1, $bonus);
    }

    public function listeningTenTrack(ListeningTenTrackEvent $listeningTenTrackEvent): void
    {
        $user = $listeningTenTrackEvent->getUser();
        $this->accrueBonusService->process(BonusProgramTypesInterfaces::LISTENING_TEN_TRACKS, $user);
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
        if (false === $user->isDirty('avatar')) {
            return;
        }
        $this->accrueBonusService->process(BonusProgramTypesInterfaces::AVATAR, $user);
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
        if (false === $user->isDirty('username')) {
            return;
        }
        $this->accrueBonusService->process(BonusProgramTypesInterfaces::USER_NAME, $user);
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
        if (false === $user->isDirty('city_id')) {
            return;
        }
        $this->accrueBonusService->process(BonusProgramTypesInterfaces::CITY, $user);
    }

    /**
     * Заполнение Города.
     *
     * @param ArtistProfile $artist
     */
    public function fillCareerStart(ArtistProfile $artist): void
    {
        $user = $artist->user;
        if (false === $artist->isDirty('career_start')) {
            return;
        }
        $this->accrueBonusService->process(BonusProgramTypesInterfaces::CAREER_START, $user);
    }

    /**
     * Заполнение описания.
     *
     * @param ArtistProfile $artist
     *
     * @throws Exception
     */
    public function fillArtistDescription(ArtistProfile $artist): void
    {
        $user = $artist->user;
        if (false === $artist->isDirty('description')) {
            return;
        }
        $this->accrueBonusService->process(BonusProgramTypesInterfaces::ARTIST_DESCRIPTION, $user);
    }

    /**
     * Заполнение жанров в которых играет.
     *
     * @param ArtistProfile $artist
     */
    public function fillArtistGenres(ArtistProfile $artist): void
    {
        $user = $artist->user;
        if (0 === $artist->genres->count()) {
            return;
        }
        $this->accrueBonusService->process(BonusProgramTypesInterfaces::ARTIST_GENRES, $user);
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
        $user = $social->user;
        $this->accrueBonusService->process(BonusProgramTypesInterfaces::SOCIALS, $user);
    }

    public function changeLevel(CompletedTaskEvent $event): void
    {
        /** @var User $user */
        $user = $event->getUser();
        $userLevel = new UserLevels();
        $userLevel->changeUserLevel($user);
    }

    public function createWatcheables(Watcheables $watcheables): void
    {
        /** @var User $user */
        $user = $watcheables->user;

        if (null === $user) {
            return;
        }

        $count = $user->watchingUser()->count();
        $extraData = $count;

        $config = config('bonus.watchingUser');

        if (false === isset($config[$count])) {
            return;
        }

        $bonus = $config[$count];

        $this->accrueBonusService->process(BonusProgramTypesInterfaces::RECEIVING_POINTS_FOR_SUBSCRIBERS, $user, 1, $bonus, $extraData);
    }

    public function createdTopFifty(CreatedTopFiftyEvent $createdTopFiftyEvent): void
    {
        $trackIdsThree = array_slice($createdTopFiftyEvent->getIdsTrack(), 0, 3);

        $trackIdsFive = array_slice($createdTopFiftyEvent->getIdsTrack(), 0, 5);
        event(new CreatedTopFiveTrack($trackIdsFive));

        $tracks = Track::query()->findMany($trackIdsThree)->all();

        foreach ($tracks as $place => $track) {
            event(new EntryTrackInTopFiftyEvent($track, $place + 1));
        }
    }

    public function entryTrackInTopFifty(EntryTrackInTopFiftyEvent $entryTrackInTopFifty): void
    {
        $track = $entryTrackInTopFifty->getTrack();
        $user = $track->user;

        $place = $entryTrackInTopFifty->getPlace();

        /** @var array $topFiftyPlacesConfig */
        $topFiftyPlacesConfig = config('bonus.topFiftyPlaces');
        if (null === $topFiftyPlacesConfig) {
            return;
        }

        $bonus = $topFiftyPlacesConfig[$place] ?? null;

        if (null === $bonus) {
            return;
        }

        $this->accrueBonusService->process(BonusProgramTypesInterfaces::ENTRY_IN_PLAYLIST_TOP_FIFTY, $user, 1, $bonus, $track->id);
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
        $this->accrueBonusService->process(BonusProgramTypesInterfaces::CONTINUOUS_PRESENCE_IN_THE_TOP_FIVE_WEEK, $user, -1);
    }

    public function topFiveMonthUser(TopFiveMonthEvent $fiveMonthEvent): void
    {
        $user = User::find($fiveMonthEvent->getIdUser());
        $this->accrueBonusService->process(BonusProgramTypesInterfaces::CONTINUOUS_PRESENCE_IN_THE_TOP_FIVE_MONTH, $user, -1);
    }
}
