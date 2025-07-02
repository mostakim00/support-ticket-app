<?php

namespace App\Listeners;

use App\Events\TicketCreated;
use App\Jobs\AssignTicketJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AssignTicketListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TicketCreated $event): void
    {
        AssignTicketJob::dispatch($event->ticket);
    }
}
