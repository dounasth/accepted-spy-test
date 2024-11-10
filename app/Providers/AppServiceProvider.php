<?php

namespace App\Providers;

use App\Application\Actions\CreateSpyAction;
use App\Application\Contracts\CreateSpyActionContract;
use App\Domain\Repositories\SpyRepository;
use App\Infrastructure\Persistence\EloquentSpyRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CreateSpyActionContract::class, CreateSpyAction::class);
        $this->app->bind(SpyRepository::class, EloquentSpyRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
