<?php

return [
    /*
    |настройки поиска
    |
    */

    'models' => [
        'track' => [
            'model' => 'App\Models\Track',
        ],
        'album' => [
            'model' => 'App\Models\Album',
        ],
        'user' => [
            'model' => 'App\User',
            'scopes' => [
                'filterRoles' => ['critic', 'prof_critic', 'star', 'performer'],
            ],
        ],
        'collection' => [
            'model' => 'App\Models\Collection',
        ],
    ],
];
