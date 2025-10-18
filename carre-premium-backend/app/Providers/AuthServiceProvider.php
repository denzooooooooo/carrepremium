<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Configure admin guard redirect
        Auth::extend('admin', function ($app, $name, array $config) {
            return tap($app->make('auth')->createSessionDriver($name, $config), function ($guard) {
                $guard->setProvider($app['auth']->createUserProvider($config['provider'] ?? null));
            });
        });
    }
}
