<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 28.02.19
 * Time: 12:09.
 */

namespace App\Http\GraphQL\Type;

use App\Models\Genre;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class GenreType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Genre',
        'model' => Genre::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the ',
                'alias' => 'id', // Use 'alias', if the database column is different from the type name
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The id of the ',
            ],
            'image' => [
                'type' => Type::string(),
                'description' => 'The id of the ',
            ],
        ];
    }
}
