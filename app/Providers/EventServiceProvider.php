<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Queue\Events\JobProcessing;
use App\Listeners\OperationsAfterProcessing;
class EventServiceProvider extends ServiceProvider
{
    /**
     * 
     * Register services.
     */
    protected $listen = [
        JobProcessing::class => [
            OperationsAfterProcessing::class,
        ],
    ];
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // parent::boot();
    }

    public function shouldDiscoverEvents()
    {
        return false;
    }
}
