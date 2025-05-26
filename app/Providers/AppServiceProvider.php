<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\Reservation;
use App\Policies\EventPolicy;
use App\Policies\ReservationPolicy;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //  Gate::policy(Event::class, EventPolicy::class);
        // Gate::policy(Reservation::class,ReservationPolicy::class);
    }
}
