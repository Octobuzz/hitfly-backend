<?php

namespace App\Http\GraphQL\InputObject\Filter;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SocialLinkFilterInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'SocialLinkFilterInput',
        'description' => 'Фильтры для социальных ссылок',
    ];

    public function fields()
    {
        return [
            'mobile' => [
                'name' => 'mobile',
                'type' => Type::boolean(),
                'description' => 'Для мобильного приложения',
            ],
        ];
    }
}
