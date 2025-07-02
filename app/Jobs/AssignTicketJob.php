<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AssignTicketJob implements ShouldQueue
{
    use Dispatchable, Queueable, InteractsWithQueue, SerializesModels;

    protected  $ticket;
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $agent = User::where('role', 'support_agent')->withCount(["tickets" => function($query){
            $query->where('status', 'open');
        }])
        ->orderBy('tickets_count')
        ->first();
        if ($agent){
            $this->ticket->update([
               'assigned_to' => $agent->id,
                'status' => 'assigned'
            ]);
        }

    }
}
