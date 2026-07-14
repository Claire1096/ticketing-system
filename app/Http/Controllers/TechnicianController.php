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
            ->avg(fn ($t) => $t->created_at->diffInMinutes($t->resolved_at) / 60);

        $avgResponseHours = Ticket::whereNotNull('first_response_at')
            ->get()
            ->avg(fn ($t) => $t->created_at->diffInMinutes($t->first_response_at) / 60);

        $categoryCounts = Ticket::selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        $totalTickets = $categoryCounts->sum();

        return view('technician.dashboard', compact(
            'tickets', 'openCount', 'resolvedThisMonth',
            'avgResolutionHours', 'avgResponseHours',
            'categoryCounts', 'totalTickets'
        ));
    }
}