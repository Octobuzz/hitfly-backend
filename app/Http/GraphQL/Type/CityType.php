<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 01.04.19
 * Time: 14:46.
 */

namespace App\Http\GraphQL\Type;

use App\Models\City;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class CityType extends GraphQLType
{
    protected $attributes = [
        'name' => 'CityType',
        'description' => 'Локализация',
        'model' => City::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Id',
                'alias' => 'id', // Use 'alias', if the database column is different from the type name
            ],
            'area_region' => [
                'type' => Type::string(),
                'description' => 'Регион',
            ],
            'title' => [
                'type' => Type::string(),
                'description' => 'Название города',
            ],
        ];
    }
}
