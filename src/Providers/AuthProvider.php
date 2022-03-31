<?php

namespace Usoft\Auth\Providers;

use Illuminate\Support\ServiceProvider;

class AuthProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/cache.php.php' => config_path('cache.php'),
        ], 'cache');
    }

    public function register()
    {
    }
}
