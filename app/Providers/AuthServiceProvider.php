<?php

namespace App\Providers;

use App\Services\Auth\JsonGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy', //todo
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        /*
         * @var \Illuminate\Foundation\Application $app
         */
        Auth::extend('json', function ($app, $name, array $config) {
            // Return an instance of Illuminate\Contracts\Auth\Guard...
            return new JsonGuard($name, Auth::createUserProvider($config['provider']), $app['request']);
        });
    }
}
