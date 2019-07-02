<?php

namespace App\Http\GraphQL\Query;

use App\BuisnessLogic\TopFifty;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Cache;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class GetListenedNowUser extends Query
{
    protected $attributes = [
        'name' => 'GetListenedNowUser',
        'description' => 'Количество пользователей "Слушают сейчас"',
    ];

    public function type()
    {
        return Type::int();
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        $countUser = Cache::get(TopFifty::LISTENED_USER_KEY_CALCULATED, 0);

        return $countUser;
    }
}
