<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Support\BusinessHours;
use Illuminate\Http\Request;
use App\Exports\KpiReportExport;
use Maatwebsite\Excel\Facades\Excel;

class TechnicianController extends Controller
{
    public function dashboard(Request $request)
{
    $tickets = Ticket::latest()->get();

    $totalIssues = $tickets->count();

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
    ->whereNotNull('first_response_at')
    ->get()
    ->avg(fn ($t) => BusinessHours::diffInHours($t->first_response_at, $t->resolved_at));

    $avgResponseHours = Ticket::whereNotNull('first_response_at')
        ->get()
        ->avg(fn ($t) => BusinessHours::diffInHours($t->created_at, $t->first_response_at));

    $categoryCounts = Ticket::selectRaw('category, count(*) as total')
        ->groupBy('category')
        ->pluck('total', 'category');

    $departmentCounts = Ticket::selectRaw('department, count(*) as total')
        ->whereNotNull('department')
        ->groupBy('department')
        ->pluck('total', 'department');

    // Date-range filter for the "records" panel
    $startDate = $request->query('start_date');
    $endDate = $request->query('end_date');

    $filteredQuery = Ticket::query();
    if ($startDate && $endDate) {
        $filteredQuery->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
    }
    $filteredTotal = (clone $filteredQuery)->count();
    $filteredResolved = (clone $filteredQuery)->where('status', 'Resolved')->count();
    $filteredOpen = (clone $filteredQuery)->whereIn('status', ['Open', 'In Progress', 'Pending'])->count();

    $recentTickets = Ticket::latest()->take(2)->get();

    // Monthly Ticket History
    $selectedMonth = $request->query('month', now()->format('Y-m'));
    $monthStart = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
    $monthEnd = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->endOfMonth();

    $monthlyTotal = Ticket::whereBetween('created_at', [$monthStart, $monthEnd])->count();
    $monthlyResolved = Ticket::whereBetween('created_at', [$monthStart, $monthEnd])
        ->where('status', 'Resolved')
        ->count();
    $monthlyUnresolved = $monthlyTotal - $monthlyResolved;
    $monthlyResolvedPercent = $monthlyTotal > 0 ? round(($monthlyResolved / $monthlyTotal) * 100) : 0;
    $monthlyUnresolvedPercent = $monthlyTotal > 0 ? 100 - $monthlyResolvedPercent : 0;

    $monthOptions = [];
    for ($i = 0; $i < 6; $i++) {
        $m = now()->subMonths($i);
        $monthOptions[$m->format('Y-m')] = $m->format('F Y');
    }

    return view('technician.dashboard', compact(
        'tickets', 'openCount', 'resolvedThisMonth', 'totalIssues',
        'acknowledgedCount', 'resolvedCount', 'resolvedPercent',
        'avgResolutionHours', 'avgResponseHours',
        'categoryCounts', 'departmentCounts',
        'startDate', 'endDate', 'filteredTotal', 'filteredResolved', 'filteredOpen',
        'recentTickets',
        'selectedMonth', 'monthlyTotal', 'monthlyResolved', 'monthlyUnresolved',
        'monthlyResolvedPercent', 'monthlyUnresolvedPercent', 'monthOptions'
    ));
}

   public function allTickets(Request $request)
{
    // Changed 'user' to 'submittedBy' to match your Ticket model relationship name
    $query = Ticket::query()->with('submittedBy');

    // 1. Filter by Month
    if ($request->filled('month')) {
        $query->whereMonth('created_at', $request->month);
    }

    // 2. Filter by Year
    if ($request->filled('year')) {
        $query->whereYear('created_at', $request->year);
    }

    // 3. Fetch paginated results with the filter variables attached
    $tickets = $query->latest()->paginate(15)->withQueryString();

    return view('tickets.all', compact('tickets'));
}

    public function exportKpiReport(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $filename = 'KPI_Report_' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new KpiReportExport($startDate, $endDate), $filename);
    }
}