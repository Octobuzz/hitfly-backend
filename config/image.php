<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default PHP's "GD Library" implementation is used.
    |
    | Supported: "gd", "imagick"
    |
    */

    'driver' => 'gd',

    //размеры изображений
    'size' => [
        'avatar' =>[
            'default' => [
                'width'=>235,
                'height'=>235,
            ],
            'size_56x56' => [
                'width'=>56,
                'height'=>56,
            ],
            'size_235x235' => [
                'width'=>235,
                'height'=>235,
            ],
        ],
        'album' =>[
            'size_50x50' => [
                'width'=>56,
                'height'=>56,
            ],
            'size_60x30' => [
                'width'=>56,
                'height'=>56,
            ]
        ],


    ],

];
