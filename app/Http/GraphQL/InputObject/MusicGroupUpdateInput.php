<?php

namespace App\Http\GraphQL\InputObject;

use GraphQL\Type\Definition\Type;

class MusicGroupUpdateInput extends MusicGroupInput
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'MusicGroupUpdateInput',
        'description' => 'Update music group',
    ];

    public function fields()
    {
        $return = parent::fields();

        return array_merge($return, [
            'activeMembers' => [
                'name' => 'activeMembers',
                'description' => 'ID вступивших в группу пользователей',
                'type' => Type::listOf(Type::int()),
            ],
        ]);
    }
}
