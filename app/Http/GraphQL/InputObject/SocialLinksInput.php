<?php

namespace App\Http\GraphQL\InputObject;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SocialLinksInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'SocialLinksInput',
        'description' => 'Социальные ссылки группы',
    ];

    public function fields()
    {
        return [
            'socialType' => [
                'name' => 'socialType',
                'description' => 'тип соцсети',
                'type' => Type::nonNull(\GraphQL::type('SocialLinksTypeEnum')),
                //'rules' => ['max:250', 'required'],
            ],
            'link' => [
                'name' => 'link',
                'description' => 'ссылка на профиль',
                'type' => Type::nonNull(Type::string()),
                //'rules' => ['date', 'required'],
            ],
        ];
    }
}
