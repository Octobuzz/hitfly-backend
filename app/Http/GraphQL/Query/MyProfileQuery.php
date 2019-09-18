<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 05.04.19
 * Time: 16:40.
 */

namespace App\Http\GraphQL\Query;

use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\Type;

class MyProfileQuery extends Query
{
    protected $attributes = [
        'name' => 'MyProfile',
        'description' => 'Мой профиль',
    ];

    public function type()
    {
        return \GraphQL::type('MyProfile');
    }

    public function args()
    {
        return [
        ];
    }

    public function resolve($root, $args, SelectFields $fields, ResolveInfo $info)
    {
        $user = Auth::guard('json')->user();
        if (null === $user) {
            return null;
        }

        return User::with($fields->getRelations())->where('id', '=', $user->id)->first();
    }
}
