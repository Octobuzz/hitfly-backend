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
];
