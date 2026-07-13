<?php

namespace App\Http\Controllers;

use App\Models\Ticket;

class TechnicianController extends Controller
{
    public function dashboard()
    {
        $tickets = Ticket::latest()->get();

        $openCount = Ticket::whereIn('status', ['Open', 'In Progress', 'Pending'])->count();
        $resolvedThisMonth = Ticket::where('status', 'Resolved')
            ->whereMonth('resolved_at', now()->month)
            ->count();

        $avgResolutionHours = Ticket::whereNotNull('resolved_at')
            ->get()
            ->avg(fn ($t) => $t->created_at->diffInHours($t->resolved_at));

        return view('technician.dashboard', compact(
            'tickets', 'openCount', 'resolvedThisMonth', 'avgResolutionHours'
        ));
    }
}