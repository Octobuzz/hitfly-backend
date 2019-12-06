<?php

return [
    'levelBonusPoint' => [
        \App\User::LEVEL_NOVICE => 0,
        \App\User::LEVEL_AMATEUR => 400,
        \App\User::LEVEL_CONNOISSEUR_OF_THE_GENRE => 3000,
        \App\User::LEVEL_SUPER_MUSIC_LOVER => 5000,
    ],

    'levelListenTrack' => [
        \App\User::LEVEL_NOVICE => 0,
        \App\User::LEVEL_AMATEUR => 0,
        \App\User::LEVEL_CONNOISSEUR_OF_THE_GENRE => 2500,
        \App\User::LEVEL_SUPER_MUSIC_LOVER => 10000,
    ],
    'topFiftyPlaces' => [
        1 => 5000,
        2 => 4000,
        3 => 3000,
    ],
    'entranceApp' => [
        2 => 3,
        3 => 6,
        4 => 12,
        5 => 20,
        6 => 35,
    ],
    'watchingUser' => [
        1 => 30,
        10 => 50,
        50 => 120,
        100 => 150,
        500 => 1200,
        1000 => 1500,
        5000 => 12000,
    ],
    //настройки расчета топ 50
    'topFifty' => [
        'storeDays' => 31, //хранить сырой массив для топа Х дней
        'topDays' => 7, //за сколько дней брать данные для топ-а
    ],
];
