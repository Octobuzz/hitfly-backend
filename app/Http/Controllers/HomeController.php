<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Genre;
use App\Models\News;
use App\Services\Auth\JsonGuard;
use App\User;
use Auth;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @param Request $request
     *
     * @return Renderable
     */
    public function index(Request $request): Renderable
    {
        if (null !== Auth::user()) {
            Cookie::queue(JsonGuard::HEADER_NAME_TOKEN, Auth::user()->access_token, 864000);
        }

        $title = 'Hitfly';
        $description = 'Слушай лучшие подборки музыки онлайн. Публикуй собственную музыку и зарабатывай на этом вместе с Hitfly';

        return view('spa', [
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function recommended()
    {
        $title = 'Рекомендаци Hitfly';
        $description = 'Рекомендаци Hitfly - в сервисе  для музыкантов и меломанов HitFly';

        return view('spa', [
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function superMelomaniac()
    {
        $title = 'Супермеломан Hitfly';
        $description = 'Супермеломан Hitfly - в сервисе  для музыкантов и меломанов HitFly';

        return view('spa', [
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function topFifty()
    {
        $title = 'TOP-50 Hitfly';
        $description = 'TOP-50 Hitfly - в сервисе  для музыкантов и меломанов HitFly';

        return view('spa', [
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function listeningNow()
    {
        $title = 'Слушают сейчас Hitfly';
        $description = 'Слушают сейчас Hitfly - в сервисе  для музыкантов и меломанов HitFly';

        return view('spa', [
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function weeklyTop()
    {
        $title = 'Лучшее за неделю Hitfly';
        $description = 'Лучшее за неделю в сервисе  для музыкантов и меломанов HitFlyy';

        return view('spa', [
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function newSongs()
    {
        $title = 'Новые песни Hitfly';
        $description = 'Новые песни Hitfly - в сервисе  для музыкантов и меломанов HitFly';

        return view('spa', [
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function genre(Genre $genre)
    {
        $genreName = $genre->name;
        $title = "Страница жанра $genreName";
        $description = "$title - в сервисе  для музыкантов и меломанов HitFlyy";

        return view('spa', [
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function playlist(Collection $playlist)
    {
        $title = $playlist->title;
        $description = "$title - в сервисе  для музыкантов и меломанов HitFly";

        return view('spa', [
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function news(News $news)
    {
        $title = $news->title;
        $description = "$title - в сервисе  для музыкантов и меломанов HitFly";

        return view('spa', [
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function bonusProgram()
    {
        $title = 'Бонусная программа';
        $description = 'Бонусная программа - в сервисе  для музыкантов и меломанов HitFly';

        return view('spa', [
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function faq()
    {
        $title = 'FAQ Hitfly';
        $description = 'FAQ Hitfly - в сервисе  для музыкантов и меломанов HitFly';

        return view('spa', [
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function user(User $user)
    {
        $title = 'Рекомендаци Hitfly';
        $description = 'Рекомендаци Hitfly - в сервисе  для музыкантов и меломанов HitFly';

        return view('spa', [
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function userMusic(User $user)
    {
        $userName = $user->name;
        $title = "$userName музыка";
        $description = "$userName - музыка в сервисе  для музыкантов и меломанов HitFly";

        return view('spa', [
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function userMusicTracks(User $user)
    {
        $userName = $user->name;
        $title = "$userName треки";
        $description = "$userName - треки в сервисе  для музыкантов и меломанов HitFly";

        return view('spa', [
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function userMusicPlaylists(User $user)
    {
        $userName = $user->name;
        $title = "$userName плайлисты";
        $description = "$userName - плайлисты в сервисе  для музыкантов и меломанов HitFly";

        return view('spa', [
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function userReviews(User $user)
    {
        $userName = $user->name;
        $title = "$userName отзывы";
        $description = "$userName - отзывы в сервисе  для музыкантов и меломанов HitFly";

        return view('spa', [
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function userUserReviews(User $user)
    {
        $userName = $user->name;
        $title = "$userName оставленные отзывы";
        $description = "$userName - оставленные отзывы в сервисе  для музыкантов и меломанов HitFly";

        return view('spa', [
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function upload()
    {
        $title = 'Рекомендаци Hitfly';
        $description = 'Рекомендаци Hitfly - в сервисе  для музыкантов и меломанов HitFly';

        return view('spa', [
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function about()
    {
        $title = 'Рекомендаци Hitfly';
        $description = 'Рекомендаци Hitfly - в сервисе  для музыкантов и меломанов HitFly';

        return view('spa', [
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function policy()
    {
        return view('policy');
    }
}
