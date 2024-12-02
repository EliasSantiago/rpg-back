<?php

namespace App\Providers;

use App\Repositories\AuthRepositoryInterface;
use App\Repositories\Eloquent\AuthRepository;
use App\Repositories\Eloquent\GuildRepository;
use App\Repositories\Eloquent\RpgClassesRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\GuildRepositoryInterface;
use App\Repositories\RpgClassesRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            AuthRepositoryInterface::class,
            AuthRepository::class,
        );

        $this->app->singleton(
            UserRepositoryInterface::class,
            UserRepository::class,
        );

        $this->app->singleton(
            GuildRepositoryInterface::class,
            GuildRepository::class,
        );

        $this->app->singleton(
            RpgClassesRepositoryInterface::class,
            RpgClassesRepository::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
