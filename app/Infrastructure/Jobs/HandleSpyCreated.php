<?php

namespace App\Infrastructure\Jobs;

use App\Domain\Events\SpyCreated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class HandleSpyCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public SpyCreated $event;

    /**
     * Create a new job instance.
     *
     * @param SpyCreated $event
     */
    public function __construct(SpyCreated $event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Example: Log spy creation, send a notification, etc.
        Log::info("Spy created: {$this->event->spy->getName()->fullName()}");
    }

}
