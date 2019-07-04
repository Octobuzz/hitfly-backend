<?php

return [
    'straight' => [
        'type' => 'state_machine',
        'marking_store' => [
            'type' => 'multiple_state',
            'arguments' => ['state'],
        ],
        'supports' => ['App\Models\Track'],
        'places' => ['draft', 'review', 'rejected', 'published'],
        'transitions' => [
            'to_review' => [
                'from' => 'draft',
                'to' => 'review',
            ],
            'publish' => [
                'from' => 'review',
                'to' => 'published',
            ],
            'reject' => [
                'from' => 'review',
                'to' => 'rejected',
            ],
        ],
    ],
];
