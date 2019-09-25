<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Comment;
use App\Policies\CommentPolicy;

class CommentServiceProvider extends ServiceProvider
{
    protected $policies = [
       Comment::class => CommentPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register()
    {
        $this->registerPolicies();
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
    }
}
