<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function index()
    {
        $userId = 1; // Simulated logged-in user
        $tickets = Ticket::where('user_id', $userId)->get();
        return view('index', compact('tickets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:Low,Medium,High',
        ]);

        $ticket = Ticket::create(array_merge($validated, [
            'user_id' => 1, // Simulated logged-in user
        ]));

        return response()->json([
            'message' => 'Ticket Submitted Successfully',
            'ticket' => $ticket
        ], 201);
    }

    public function topSupportAgents()
    {
        $agents = DB::select("
            SELECT u.name, COUNT(t.id) as ticket_count
            FROM users u
            LEFT JOIN tickets t ON u.id = t.assigned_to
            WHERE u.role = 'support_agent'
            AND t.created_at >= NOW() - INTERVAL 7 DAY
            GROUP BY u.id, u.name
            ORDER BY ticket_count DESC
            LIMIT 3
        ");

        return response()->json($agents);
    }
}
