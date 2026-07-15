<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Support\BusinessHours;

use Carbon\Carbon;

class TechnicianController extends Controller
{
    // 1. The Dashboard Method (Fixed unreachable code issue)
    public function dashboard()
    {
        $tickets = Ticket::latest()->get();
        $totalIssues = $tickets->count();

        // REMOVED the early return view statement that was breaking this method

        $openCount = Ticket::whereIn('status', ['Open', 'In Progress', 'Pending'])->count();
        $acknowledgedCount = Ticket::whereNotNull('first_response_at')
            ->whereNotIn('status', ['Resolved', 'Closed'])
            ->count();
        $resolvedCount = Ticket::where('status', 'Resolved')->count();
        $resolvedPercent = $totalIssues > 0 ? round(($resolvedCount / $totalIssues) * 100) : 0;

        $resolvedThisMonth = Ticket::where('status', 'Resolved')
            ->whereMonth('resolved_at', now()->month)
            ->count();

        $avgResolutionHours = Ticket::whereNotNull('resolved_at')
            ->get()
            ->avg(fn ($t) => BusinessHours::diffInHours($t->created_at, $t->resolved_at));

        $avgResponseHours = Ticket::whereNotNull('first_response_at')
            ->get()
            ->avg(fn ($t) => BusinessHours::diffInHours($t->created_at, $t->first_response_at));

        $categoryCounts = Ticket::selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        // Trend data: last 14 days
        $chartLabels = [];
        $complaintsData = [];
        $acknowledgedData = [];
        $resolvedData = [];

        for ($i = 13; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $chartLabels[] = $day->format('M j');

            $complaintsData[] = Ticket::whereDate('created_at', $day)->count();
            $acknowledgedData[] = Ticket::whereDate('first_response_at', $day)->count();
            $resolvedData[] = Ticket::whereDate('resolved_at', $day)->count();
        }

        return view('technician.dashboard', compact(
            'tickets', 'openCount', 'resolvedThisMonth', 'totalIssues',
            'acknowledgedCount', 'resolvedCount', 'resolvedPercent',
            'avgResolutionHours', 'avgResponseHours',
            'categoryCounts',
            'chartLabels', 'complaintsData', 'acknowledgedData', 'resolvedData'
        ));
    }

    // 2. Added the missing allTickets method expected by your route
    public function allTickets()
    {
        $tickets = Ticket::latest()->get();
        
        return view('tickets.all', compact('tickets'));
    }

    public function myMetrics()
{
    $techId = auth()->id(); // Get the logged-in technician's ID

    // Get ONLY this month's resolved tickets assigned to this technician
    $resolvedTickets = Ticket::where('assigned_to', $techId)
        ->whereIn('status', ['Resolved', 'Closed'])
        ->whereNotNull('resolved_at')
        ->whereMonth('resolved_at', now()->month)
        ->whereYear('resolved_at', now()->year)
        ->get();

    $totalWorkingHours = 0;
    $totalResponseMinutes = 0;
    $respondedTicketsCount = 0;

    // Business Hours (9:00 AM to 6:00 PM)
    $startHour = 9;
    $endHour = 18;

    foreach ($resolvedTickets as $ticket) {
        $created = Carbon::parse($ticket->created_at);
        $resolved = Carbon::parse($ticket->resolved_at);

        // 1. Calculate Working Hours (Resolution Time)
        $currentDate = $created->copy();
        $ticketWorkingHours = 0;

        while ($currentDate->format('Y-m-d') <= $resolved->format('Y-m-d')) {
            if ($currentDate->isWeekday()) {
                $bStart = $currentDate->copy()->hour($startHour)->minute(0)->second(0);
                $bEnd = $currentDate->copy()->hour($endHour)->minute(0)->second(0);

                $actStart = $created->gt($bStart) && $created->isSameDay($currentDate) ? $created : $bStart;
                $actEnd = $resolved->lt($bEnd) && $resolved->isSameDay($currentDate) ? $resolved : $bEnd;

                if ($actStart->lt($actEnd)) {
                    $ticketWorkingHours += $actStart->diffInMinutes($actEnd) / 60;
                }
            }
            $currentDate->addDay()->startOfDay();
        }
        $totalWorkingHours += $ticketWorkingHours;

        // 2. Calculate Response Time (In Minutes)
        if ($ticket->first_response_at) {
            $responseAt = Carbon::parse($ticket->first_response_at);
            $totalResponseMinutes += $created->diffInMinutes($responseAt);
            $respondedTicketsCount++;
        }
    }

    // Convert total working resolution hours to minutes for granular displays, or keep as hours
    $avgResolutionTime = $resolvedTickets->count() > 0 
        ? round($totalWorkingHours / $resolvedTickets->count(), 1) 
        : 0;

    $avgResponseTime = $respondedTicketsCount > 0 
        ? round(($totalResponseMinutes / $respondedTicketsCount), 0) // Average in minutes
        : 0;

    return view('tech.metrics', compact('avgResolutionTime', 'avgResponseTime', 'resolvedTickets'));
}
}