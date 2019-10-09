<?php

/* Simple configuration file for Laravel Sitemap package */
return [
    'staticPages' => [
        ['url' => '/', 'modified' => null, 'priority' => '1.0', 'freq' => 'daily'],
        ['url' => '/recommended', 'modified' => null, 'priority' => '1.0', 'freq' => 'daily'],
        ['url' => '/super-melomaniac', 'modified' => null, 'priority' => '1.0', 'freq' => 'daily'],
        ['url' => '/top50', 'modified' => null, 'priority' => '1.0', 'freq' => 'daily'],
        ['url' => '/listening_now', 'modified' => null, 'priority' => '1.0', 'freq' => 'daily'],
        ['url' => '/weekly_top', 'modified' => null, 'priority' => '1.0', 'freq' => 'daily'],
        ['url' => '/new_songs', 'modified' => null, 'priority' => '1.0', 'freq' => 'daily'],
        ['url' => '/bonus-program', 'modified' => null, 'priority' => '1.0', 'freq' => 'monthly'],
        ['url' => '/faq', 'modified' => null, 'priority' => '1.0', 'freq' => 'monthly'],
        ['url' => '/about', 'modified' => null, 'priority' => '1.0', 'freq' => 'monthly'],
        ['url' => '/reviews', 'modified' => null, 'priority' => '1.0', 'freq' => 'daily'],
        ['url' => '/register', 'modified' => null, 'priority' => '1.0', 'freq' => 'monthly'],
        ['url' => '/login', 'modified' => null, 'priority' => '1.0', 'freq' => 'monthly'],
    ],

    'genres' => ['url' => 'genre', 'priority' => '0.5', 'freq' => 'monthly'],
    'playlists' => ['url' => 'playlist', 'priority' => '0.5', 'freq' => 'daily'],
    'news' => ['url' => 'news', 'priority' => '0.5', 'freq' => 'daily'],
    'users' => [
        'listener' => [
            ['url' => 'user', 'postfix' => '/music', 'priority' => '0.5', 'freq' => 'daily'],
            ['url' => 'user', 'postfix' => '/music/reviews', 'priority' => '0.5', 'freq' => 'daily'],
        ],
        'performer' => [
            ['url' => 'user', 'postfix' => '/music', 'priority' => '0.5', 'freq' => 'daily'],
            ['url' => 'user', 'postfix' => '/music/reviews', 'priority' => '0.5', 'freq' => 'daily'],
            ['url' => 'user', 'postfix' => '/music/tracks', 'priority' => '0.5', 'freq' => 'daily'],
            ['url' => 'user', 'postfix' => '/music/albums', 'priority' => '0.5', 'freq' => 'daily'],
            ['url' => 'user', 'postfix' => '/music/playlists', 'priority' => '0.5', 'freq' => 'daily'],
            ['url' => 'user', 'postfix' => '/reviews', 'priority' => '0.5', 'freq' => 'daily'],
        ],
        'critic' => [
            ['url' => 'user', 'postfix' => '/music', 'priority' => '0.5', 'freq' => 'daily'],
            ['url' => 'user', 'postfix' => '/music/reviews', 'priority' => '0.5', 'freq' => 'daily'],
            ['url' => 'user', 'postfix' => '/music/tracks', 'priority' => '0.5', 'freq' => 'daily'],
            ['url' => 'user', 'postfix' => '/music/albums', 'priority' => '0.5', 'freq' => 'daily'],
            ['url' => 'user', 'postfix' => '/music/playlists', 'priority' => '0.5', 'freq' => 'daily'],
            ['url' => 'user', 'postfix' => '/reviews', 'priority' => '0.5', 'freq' => 'daily'],
            ['url' => 'user', 'postfix' => '/user-reviews', 'priority' => '0.5', 'freq' => 'daily'],
        ],
        'star' => [
            ['url' => 'user', 'postfix' => '/user-reviews', 'priority' => '0.5', 'freq' => 'daily'],
        ],
        //комменты на трек
        'reviews' => ['url' => 'user', 'postfix' => '/reviews', 'priority' => '0.2', 'freq' => 'monthly'],
        //альбомы
        'albums' => ['url' => 'user', 'postfix' => '/album', 'priority' => '0.2', 'freq' => 'monthly'],
        //плейлисты
        'playlists' => ['url' => 'user', 'postfix' => '/playlist', 'priority' => '0.2', 'freq' => 'monthly'],
        ],
];
