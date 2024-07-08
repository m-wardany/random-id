<?php

namespace MWardany\HashIds\Providers;


class AppServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/hashid.php',
            'hashid'
        );
    }

    public function boot()
    {
        $this->app->register(EventServiceProvider::class);
        $this->publishes([
            __DIR__ . '/../config/hashid.php' => config_path('hashid.php'),
        ], 'config');
    }
}
