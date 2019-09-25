<?php

namespace App\Http\GraphQL\Enums;

use Rebing\GraphQL\Support\Type as GraphQLType;

class CommentPeriodEnum extends GraphQLType
{
    protected $enumObject = true;

    protected $attributes = [
        'name' => 'CommentPeriodEnum',
        'description' => 'Периоды комментирования',
        'values' => [
            'week' => 'week',
            'month' => 'month',
            'year' => 'year',
        ],
    ];
}
