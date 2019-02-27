<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Comment;
use App\Policies\CommentPolicy;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
class CommentServiceProvider extends ServiceProvider
{
    protected $policies = [
       Comment::class => CommentPolicy::class
    ];
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
         $this->registerPolicies();

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
