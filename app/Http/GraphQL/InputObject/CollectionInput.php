<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 05.03.19
 * Time: 15:07.
 */

namespace App\Http\GraphQL\InputObject;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class CollectionInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'CollectionInput',
        'description' => 'Add new collection',
    ];

    public function fields()
    {
        return [
            'title' => [
                'name' => 'title',
                'description' => 'name',
                'type' => Type::nonNull(Type::int()),
                'rules' => ['max:250'],
            ],
        ];
    }
}
