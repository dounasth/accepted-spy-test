<?php

namespace App\Providers;

use App\Application\Actions\CreateSpyAction;
use App\Application\Contracts\CreateSpyActionContract;
use App\Application\Contracts\ListSpiesQueryContract;
use App\Application\Queries\ListSpiesQuery;
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
        $this->app->bind(ListSpiesQueryContract::class, function ($app) {
            return new ListSpiesQuery($app->make(SpyRepository::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
