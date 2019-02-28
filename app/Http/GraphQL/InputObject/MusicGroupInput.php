<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 28.02.19
 * Time: 17:14.
 */

namespace App\Http\GraphQL\InputObject;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class MusicGroupInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'MusicGroupInput',
        'description' => 'Add new music group',
    ];

    public function fields()
    {
        return [
            'name' => [
                'name' => 'name',
                'description' => 'name',
                'type' => Type::string(),
                'rules' => ['max:250'],
            ],
            'careerStartYear' => [
                'name' => 'careerStartYear',
                'description' => 'careerStartYear',
                'type' => Type::string(),
                'rules' => ['min:0', 'max:5'],
            ],
            'description' => [
                'name' => 'description',
                'description' => 'description',
                'type' => Type::string(),
                'rules' => ['min:0', 'max:5'],
            ],
        ];
    }
}
