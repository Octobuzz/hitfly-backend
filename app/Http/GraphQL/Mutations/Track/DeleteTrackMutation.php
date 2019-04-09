<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 05.04.19
 * Time: 18:27.
 */

namespace App\Http\GraphQL\Mutations\Track;

use App\Models\Track;
use Illuminate\Support\Facades\Gate;
use Rebing\GraphQL\Error\ValidationError;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;

class DeleteTrackMutation extends Mutation
{
    protected $attributes = [
        'name' => 'RemoveTrack',
        'description' => 'Удаление трека',
    ];

    public function type()
    {
        return \GraphQL::type('Track');
    }

    public function args()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Индетификатор удаляемого трека',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $track = Track::query()->find($args['id']);

        if (Gate::allows('delete', $track)) {
            $track->delete();

            return $track;
        } else {
            return new ValidationError('Не достаточно прав на удаление');
        }
    }
}
