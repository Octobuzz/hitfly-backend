<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 05.04.19
 * Time: 18:27.
 */

namespace App\Http\GraphQL\Mutations\Track;

use App\Models\Track;
use App\Rules\OwnerTrack;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;

class RemoveTrackFromMusicGroupMutation extends Mutation
{
    protected $attributes = [
        'name' => 'RemoveTrackFromMusicGroup',
        'description' => 'Отвязка трека от группы',
    ];

    public function type()
    {
        return \GraphQL::type('Track');
    }

    public function authorize(array $args)
    {
        return Auth::check();
    }

    public function args()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Индетификатор отвязываемого трека',
                'rules' => [new OwnerTrack()],
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $track = Track::query()->find($args)->first();
        $track->music_group_id = null;
        $track->save();
    }
}
