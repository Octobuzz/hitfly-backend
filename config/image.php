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
    'factor' => 1.5,

    //размеры изображений
    'size' => [
        'allSizes' => [
            'size_56x56' => [
                'width' => 56,
                'height' => 56,
            ],
            'size_235x235' => [
                'width' => 235,
                'height' => 235,
            ],
            'size_120x120' => [
                'width' => 120,
                'height' => 120,
            ],
            'size_40x40' => [
                'width' => 40,
                'height' => 40,
            ],
            'size_104x104' => [
                'width' => 104,
                'height' => 104,
            ],
            'size_48x48' => [
                'width' => 48,
                'height' => 48,
            ],
            'size_32x32' => [
                'width' => 32,
                'height' => 32,
            ],
            'size_160x160' => [
                'width' => 160,
                'height' => 160,
            ],
            'size_150x150' => [
                'width' => 150,
                'height' => 150,
            ],
            'size_290x290' => [
                'width' => 290,
                'height' => 290,
            ],
            'size_300x300' => [
                'width' => 300,
                'height' => 300,
            ],
            'size_800x800' => [
                'width' => 800,
                'height' => 800,
            ],
        ],
        'avatar' => [
            'default' => [
                'width' => 1500,
                'height' => 1500,
            ],
            'size_56x56' => [
                'width' => 56,
                'height' => 56,
            ],
            'size_72x72' => [
                'width' => 72,
                'height' => 72,
            ],
            'size_235x235' => [
                'width' => 235,
                'height' => 235,
            ],
        ],
        'album' => [
            'default' => [
                'width' => 1500,
                'height' => 1500,
            ],
        ],
        'collection' => [
            'default' => [
                'width' => 1500,
                'height' => 1500,
            ],
        ],
        'music_group' => [
            'default' => [
                'width' => 1500,
                'height' => 1500,
            ],
        ],
    ],
];
