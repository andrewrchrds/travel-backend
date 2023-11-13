<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Itinerary;
use App\Models\ItineraryItem;
use App\Policies\ItineraryPolicy;
use App\Policies\ItineraryItemPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Itinerary::class  => ItineraryPolicy::class,
        ItineraryItem::class => ItineraryItemPolicy::class,
    ];    

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
