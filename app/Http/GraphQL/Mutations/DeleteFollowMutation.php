<?php

namespace App\Http\GraphQL\Mutations;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\MusicGroup;
use App\Models\Watcheables;
use App\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class DeleteFollowMutation extends Mutation
{
    use GraphQLAuthTrait;
    protected $attributes = [
        'name' => 'DeleteFollow',
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
                'rules' => 'follow_delete_validate',
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
                throw new \InvalidArgumentException('Не удалось определить тип Подписки');
        }

        $user = $this->getGuard()->user();

        $favourite = Watcheables::query()
            ->where('watcheable_id', $args['Follow']['FollowId'])
            ->where('watcheable_type', $class)
            ->where('user_id', $user->id)
            ->first();

        if (null === $favourite) {
            throw new \Exception('inncorect');
        } else {
            $favourite->delete();
        }

        return null;
    }
}
