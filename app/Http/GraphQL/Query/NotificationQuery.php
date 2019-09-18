<?php

namespace App\Http\GraphQL\Query;

use App\Models\Notification;
use App\Notifications\BaseNotification;
use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class NotificationQuery extends Query
{
    protected $attributes = [
        'name' => 'MyProfile',
        'description' => 'Мой профиль',
    ];

    public function type()
    {
        return \GraphQL::paginate('NotificationType');
    }

    public function args()
    {
        return [
            'limit' => ['name' => 'limit', 'type' => Type::int()],
            'page' => ['name' => 'page', 'type' => Type::int()],
        ];
    }

    public function resolve($root, $args, SelectFields $fields, ResolveInfo $info)
    {
        $user = Auth::guard('json')->user();
        if (null === $user) {
            return null;
        }
        $query = Notification::query()
            ->where('notifiable_id', '=', $user->id)
            ->where('notifiable_type', '=', User::class)
            ->where('type', '=', BaseNotification::class)
            ->orderBy('created_at', 'desc')
            ;

        $response = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

        return $response;
    }
}
