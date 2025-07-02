<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Events\TicketCreated;
use App\Listeners\AssignTicketListener;
use App\Models\Ticket;
use App\Observers\TicketObserver;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        TicketCreated::class => [
            AssignTicketListener::class,
        ],
    ];
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Ticket::observe(TicketObserver::class);
    }
}
