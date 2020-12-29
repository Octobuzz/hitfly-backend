<?php

namespace App\Http\GraphQL\Mutations;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\MusicGroup;
use App\Models\Watcheables;
use App\User;
use Rebing\GraphQL\Support\Mutation;

class FollowMutation extends Mutation
{
    use GraphQLAuthTrait;
    protected $attributes = [
        'name' => 'AddToFollow',
        'description' => 'Подписаться(Follow)',
    ];

    public function type()
    {
        return \GraphQL::type('FollowResult');
    }

    public function args()
    {
        return [
            'Follow' => [
                'type' => \GraphQL::type('FollowInput'),
                'rules' => ['follow_unique_validate', 'follow_myself_validate'],
            ],
        ];
    }

    public function resolve($root, $args)
    {
        switch ($args['Follow']['FollowType']) {
            case Watcheables::TYPE_USER:
                $class = User::class;
                break;
            case Watcheables::TYPE_MUSIC_GROUP:
                $class = MusicGroup::class;
                break;
            default:
                throw new \Exception('Не удалось определить тип Подписки');
        }

        $tmp = [];
        $tmp['watcheable_type'] = $class;
        $tmp['watcheable_id'] = $args['Follow']['FollowId'];
        $tmp['user_id'] = $this->getGuard()->user()->id;
        $follow = Watcheables::create($tmp);
        $follow->save();

        return $follow;
    }
}
