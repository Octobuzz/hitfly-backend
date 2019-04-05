<?php

namespace App\Http\GraphQL\InputObject;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class MusicGroupInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'MusicGroupInput',
        'description' => 'Add new music group',
    ];

    public function fields()
    {
        return [
            'name' => [
                'name' => 'name',
                'description' => 'name max: 250 символов',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['max:250', 'required'],
            ],
            'careerStartYear' => [
                'name' => 'careerStartYear',
                'description' => 'careerStartYear',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['date', 'required'],
            ],
            'description' => [
                'name' => 'description',
                'description' => 'description',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['min:10', 'max:1000', 'required'],
            ],
            'genre' => [
                'name' => 'genre',
                'description' => 'genre',
                'type' => Type::nonNull(Type::id()),
                'rules' => ['numeric', 'required'],
            ],
           'socialLinks' => [
                'name' => 'socialLinks',
                'description' => 'ссылки на соцсети',
                'type' => Type::listOf(\GraphQL::type('SocialLinksInput')),
                //'rules' => ['numeric', 'required'],
            ],
        ];
    }
}
