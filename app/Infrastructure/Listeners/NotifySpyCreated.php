<?php

namespace App\Infrastructure\Listeners;

use App\Domain\Events\SpyCreated;
use App\Infrastructure\Jobs\HandleSpyCreated;

class NotifySpyCreated
{
    public function handle(SpyCreated $event): void
    {
        // Handle notification or logging logic here
        HandleSpyCreated::dispatch($event);
    }
}
