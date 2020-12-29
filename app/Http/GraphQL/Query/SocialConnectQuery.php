<?php

namespace App\Http\GraphQL\Query;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Repositories\SocialRepository;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class SocialConnectQuery extends Query
{
    use GraphQLAuthTrait;
    public $authorize = true;
    protected $attributes = [
        'name' => 'SocialConnectQuery',
        'description' => 'Социальные ссылки',
    ];

    public function type()
    {
        return Type::listOf(\GraphQL::type('SocialConnectType'));
    }

    public function args()
    {
        return [
            'filters' => [
                'type' => \GraphQL::type('SocialLinkFilterInput'),
                'description' => 'Фильтры',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $socialRepository = new SocialRepository();

        return $socialRepository->getSocialConnect($args);
    }
}
