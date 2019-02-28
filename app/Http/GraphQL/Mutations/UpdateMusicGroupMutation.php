<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 28.02.19
 * Time: 17:08.
 */

namespace App\Http\GraphQL\Mutations;

use App\Models\MusicGroup;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Symfony\Component\Finder\Exception\OperationNotPermitedException;

class UpdateMusicGroupMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UpdateMusicGroup',
    ];

    public function type()
    {
        return \GraphQL::type('MusicGroup');
    }

    public function args()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the human.',
            ],
            'musicGroup' => [
                'type' => \GraphQL::type('MusicGroupInput'),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $musicGroup = MusicGroup::find($args['id']);

        if (null === $musicGroup) {
            return null;
        }

        if ($user = \Auth::user()->can('update', MusicGroup::class)) {
            throw new OperationNotPermitedException('not persmision');
        }

        $musicGroup = MusicGroup::update($args);
        $musicGroup->save();

        return $musicGroup;
    }
}
