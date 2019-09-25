<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 05.03.19
 * Time: 15:17.
 */

namespace App\Http\GraphQL\Mutations\Collection;

use App\Models\Collection;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class DeleteCollectionMutation extends Mutation
{
    protected $attributes = [
        'name' => 'DeleteCollection',
        'description' => 'Удаление коллекции поользователя',
    ];

    public function type()
    {
        return \GraphQL::type('Collection');
    }

    public function args()
    {
        return [
            'collectionId' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'id коллекции',
                'rules' => 'collection_delete_validate',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $album = Collection::query()->find($args['collectionId']);
        $album->delete();

        return null;
    }
}
