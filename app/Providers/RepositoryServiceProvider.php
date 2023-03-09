<?php

namespace App\Providers;

use App\Http\Interfaces\AdminInterface;
use App\Http\Interfaces\StationInterface;
use App\Http\Interfaces\TripInterface;
use App\Http\Repositories\AdminRepository;
use App\Http\Repositories\StationRepository;
use App\Http\Repositories\TripRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Interfaces\UserInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AdminInterface::class, AdminRepository::class);
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(TripInterface::class, TripRepository::class);
        $this->app->bind(StationInterface::class, StationRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
