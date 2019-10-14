<?php

namespace App\BuisnessLogic\Sitemap;

use App\Models\Album;
use App\Models\Collection;
use App\Models\Genre;
use App\Models\News;
use App\Models\Track;
use App\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

class SitemapGenerator
{
    public $sitemap;

    public function getMap()
    {
        $this->generateStaticPages();
        $this->generateGenres();
        $this->generatePlaylists();
        $this->generateNews();
        $this->generateUserMusic();

        $this->sitemap = App::make('sitemap');
        $this->sitemap->addSitemap(URL::to('sitemap-static.xml'));
        $this->sitemap->addSitemap(URL::to('sitemap-genres.xml'));
        $this->sitemap->addSitemap(URL::to('sitemap-playlists.xml'));
        $this->sitemap->addSitemap(URL::to('sitemap-news.xml'));
        $this->sitemap->addSitemap(URL::to('sitemap-user-music-listener.xml'));
        $this->sitemap->addSitemap(URL::to('sitemap-user-music-performer.xml'));
        $this->sitemap->addSitemap(URL::to('sitemap-user-music-critic.xml'));
        $this->sitemap->addSitemap(URL::to('sitemap-user-music-star.xml'));
        $this->sitemap->addSitemap(URL::to('sitemap-user-music-reviews.xml'));
        $this->sitemap->addSitemap(URL::to('sitemap-user-music-albums.xml'));
        $this->sitemap->addSitemap(URL::to('sitemap-user-music-playlists.xml'));

        $this->sitemap->store('sitemapindex', 'sitemap');
    }

    public function getHTMLMap()
    {
        $htmlMap = "<ul>";
        $mapCfg = config('sitemappages.html');
        foreach ($mapCfg as $item) {
            if (!empty($item['sublevel'])) {
                $htmlMap .= "<li><a href='{$item['url']}'>{$item['name']}</a><ul>";
                $htmlMap .= $this->buildRoute($item['sublevel']);
                $htmlMap .= '</ul></li>';
            }else{
                $htmlMap .= "<li><a href='{$item['url']}'>{$item['name']}</a></li>";
            }
        }
        $htmlMap .= "</ul>";
        return $htmlMap;

    }

    public function generateStaticPages()
    {
        $this->sitemap = App::make('sitemap');
        foreach (config('sitemappages.staticPages') as $page) {
            $this->sitemap->add(config('app.url').$page['url'], $page['modified'], $page['priority'], $page['freq']);
        }
        $this->sitemap->store('xml', 'sitemap-static');
    }

    /**
     * вывод жанров.
     */
    public function generateGenres()
    {
        $this->sitemap = App::make('sitemap');

        $genres = Genre::all();
        $this->generateDynamicPages($genres, config('sitemappages.genres'));
        $this->sitemap->store('xml', 'sitemap-genres');
    }

    public function generatePlaylists()
    {
        $this->sitemap = App::make('sitemap');

        $genres = Collection::query()->where('is_admin', '=', 0)->get();
        $this->generateDynamicPages($genres, config('sitemappages.playlists'));
        $this->sitemap->store('xml', 'sitemap-playlists');
    }

    public function generateNews()
    {
        $this->sitemap = App::make('sitemap');

        $genres = News::query()->orderBy('created_at', 'DESC')->get();
        $this->generateDynamicPages($genres, config('sitemappages.news'));
        $this->sitemap->store('xml', 'sitemap-news');
    }

    public function generateUserMusic()
    {
        $this->sitemap = App::make('sitemap');
        //слушатели
        $users = User::filterRoles(['listener'])->get();
        foreach (config('sitemappages.users.listener') as $conf) {
            $this->generateDynamicPages($users, $conf);
        }
        $this->sitemap->store('xml', 'sitemap-user-music-listener');
        //исполнители
        $users = User::filterRoles(['performer'])->get();
        foreach (config('sitemappages.users.performer') as $conf) {
            $this->generateDynamicPages($users, $conf);
        }
        $this->sitemap->store('xml', 'sitemap-user-music-performer');
        //критики
        $users = User::filterRoles(['critic', 'prof_critic'])->get();
        foreach (config('sitemappages.users.critic') as $conf) {
            $this->generateDynamicPages($users, $conf);
        }
        $this->sitemap->store('xml', 'sitemap-user-music-critic');
        //звезды
        $users = User::filterRoles(['star'])->get();
        foreach (config('sitemappages.users.star') as $conf) {
            $this->generateDynamicPages($users, $conf);
        }
        $this->sitemap->store('xml', 'sitemap-user-music-star');
        unset($users);

        //урлы вида: /user/{id}/review/{id}
        //обзоры
        $reviewTrack = Track::has('comments')->get();
        $this->generateTwoIds($reviewTrack, config('sitemappages.users.reviews'));
        $this->sitemap->store('xml', 'sitemap-user-music-reviews');
        unset($reviewTrack);

        //альбомы
        $albums = Album::all();
        $this->generateTwoIds($albums, config('sitemappages.users.albums'));
        $this->sitemap->store('xml', 'sitemap-user-music-albums');
        unset($albums);

        //playlist
        $playlists = Collection::query()->where('is_admin', '=', 0)->get();
        $this->generateTwoIds($playlists, config('sitemappages.users.playlists'));
        $this->sitemap->store('xml', 'sitemap-user-music-playlists');
        unset($playlists);
    }

    private function generateTwoIds($data, $conf)
    {
        $fullpath = URL::to($conf['url']).'/';
        $postfix = '';
        if (!empty($conf['postfix'])) {
            $postfix = $conf['postfix'];
        }
        foreach ($data as $page) {
            $this->sitemap->add($fullpath.$page->user_id.$postfix.'/'.$page->id, $page->updated_at, $conf['priority'], $conf['freq']);
        }
    }

    private function generateDynamicPages($data, $conf)
    {
        $fullpath = URL::to($conf['url']).'/';
        $postfix = '';
        if (!empty($conf['postfix'])) {
            $postfix = $conf['postfix'];
        }
        foreach ($data as $page) {
            $this->sitemap->add($fullpath.$page->id.$postfix, $page->updated_at, $conf['priority'], $conf['freq']);
        }
    }
}
