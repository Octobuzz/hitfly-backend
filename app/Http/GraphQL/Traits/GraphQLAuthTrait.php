<?php

namespace App\Http\GraphQL\Traits;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

trait GraphQLAuthTrait
{
    public function authorize(array $args): bool
    {
        //в некоторых запросах ненужно проверять авторизацию, но нужно подцепить guard
        if ($this->authorize) {
            return true;
        }

        return $this->getGuard()->check();
    }

    public function getGuard($guard = 'json'): Guard
    {
        return Auth::guard($guard);
    }
}
