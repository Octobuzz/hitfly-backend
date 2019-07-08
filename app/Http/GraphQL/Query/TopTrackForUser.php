<?php

namespace App\Http\GraphQL\Query;

use App\BuisnessLogic\Top\UserTop;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use Illuminate\Support\Facades\Cache;

class TopTrackForUser extends Query
{
    protected $attributes = [
        'name' => 'TopTrackForUser',
        'description' => 'получение ТОП-а треков для музыканта',
    ];

    public function type()
    {
        return Type::listOf(\GraphQL::type('Track'));
    }

    public function args()
    {
        return [
//            'limit' => ['name' => 'limit', 'type' => Type::int()],
//            'page' => ['name' => 'page', 'type' => Type::int()],
            'userId' => ['name' => 'userId', 'type' => Type::int(), 'rules' => 'required'],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        $userTopTracks = Cache::remember(UserTop::USER_TOP_CALCULATED.'_'.$args['userId'], UserTop::USER_TOP_TIME, function () use ($args) {
            return UserTop::getTopForUser($args['userId']);
        });

        return $userTopTracks;
    }
}
