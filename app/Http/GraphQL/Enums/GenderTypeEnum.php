<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 15.03.19
 * Time: 9:47.
 */

namespace App\Http\GraphQL\Enums;

use App\User;
use Rebing\GraphQL\Support\Type as GraphQLType;

class GenderTypeEnum extends GraphQLType
{
    protected $enumObject = true;

    protected $attributes = [
        'name' => 'GenderType',
        'description' => 'Пол пользователя',
        'values' => [
            User::GENDER_MEN => User::GENDER_MEN,
            User::GENDER_WOMEN => User::GENDER_WOMEN,
        ],
    ];
}
