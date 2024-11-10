<?php

namespace App\Providers;

use App\Domain\Events\SpyCreated;
use App\Infrastructure\Listeners\NotifySpyCreated;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        SpyCreated::class => [
            NotifySpyCreated::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
