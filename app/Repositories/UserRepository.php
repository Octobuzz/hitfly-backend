<?php

namespace App\Repositories;

use App\User;

class UserRepository extends SearchRepository
{
    protected $model = User::class;
}
