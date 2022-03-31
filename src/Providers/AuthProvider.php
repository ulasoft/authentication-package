<?php

namespace Usoft\Auth\Providers;

use Illuminate\Support\ServiceProvider;

class AuthProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/cache.php.php' => $this->config_path('cache.php'),
        ], 'cache');
    }

    public function register()
    {
    }

    function config_path($path = '')
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }
}
