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
        'avatar' => [
            'default' => [
                'width' => 235,
                'height' => 235,
            ],
            'size_56x56' => [
                'width' => 56,
                'height' => 56,
            ],
            'size_235x235' => [
                'width' => 235,
                'height' => 235,
            ],
        ],
        'album' => [
            'default' => [
                'width' => 120,
                'height' => 120,
            ],
            'size_120x120' => [
                'width' => 120,
                'height' => 120,
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
        ],
        'collection' => [
            'default' => [
                'width' => 214,
                'height' => 160,
            ],
            'size_214x160' => [
                'width' => 214,
                'height' => 160,
            ],
            'size_150x150' => [
                'width' => 150,
                'height' => 150,
            ],
        ],
    ],
];
