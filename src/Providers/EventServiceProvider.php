<?php

namespace MWardany\HashIds\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use MWardany\HashIds\Events\HasHashSaved;
use MWardany\HashIds\Listeners\HashAttributes;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        HasHashSaved::class => [
            HashAttributes::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
