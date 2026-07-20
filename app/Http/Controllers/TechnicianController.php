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
        // Monthly Ticket History (moved to the top — must be defined before
        // it is used in any of the queries below)
        $selectedYear = (int) $request->query('year', now()->year);

        // The month <select> submits a full "Y-m" value (e.g. "2026-06"),
        // not a bare month number — so read it as-is instead of rebuilding
        // it from a separate month number (that caused a "2026-2026" bug).
        $selectedMonth = $request->query('month', sprintf('%04d-%02d', $selectedYear, now()->month));
        $selectedMonthNum = (int) substr($selectedMonth, 5, 2);

        $monthStart = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        $monthEnd = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->endOfMonth();
        $selectedMonthLabel = $monthStart->format('F Y');

        $tickets = Ticket::whereBetween('created_at', [$monthStart, $monthEnd])->latest()->get();

        $totalIssues = $tickets->count();

        $openCount = Ticket::whereBetween('created_at', [$monthStart, $monthEnd])
            ->whereIn('status', ['Open', 'In Progress', 'Pending'])
            ->count();

        $resolvedCount = Ticket::whereBetween('created_at', [$monthStart, $monthEnd])
            ->where('status', 'Resolved')
            ->count();
        $resolvedPercent = $totalIssues > 0 ? round(($resolvedCount / $totalIssues) * 100) : 0;

        $acknowledgedCount = Ticket::whereBetween('created_at', [$monthStart, $monthEnd])
            ->where('status', 'Acknowledged')
            ->count();

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

        $categoryCounts = Ticket::whereBetween('created_at', [$monthStart, $monthEnd])
            ->selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        $departmentCounts = Ticket::whereBetween('created_at', [$monthStart, $monthEnd])
            ->whereNotNull('department')
            ->selectRaw('department, count(*) as total')
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

        $recentTickets = Ticket::whereBetween('created_at', [$monthStart, $monthEnd])
            ->latest()
            ->take(2)
            ->get();

        $monthlyTotal = Ticket::whereBetween('created_at', [$monthStart, $monthEnd])->count();
        $monthlyResolved = Ticket::whereBetween('created_at', [$monthStart, $monthEnd])
            ->where('status', 'Resolved')
            ->count();
        $monthlyUnresolved = $monthlyTotal - $monthlyResolved;
        $monthlyResolvedPercent = $monthlyTotal > 0 ? round(($monthlyResolved / $monthlyTotal) * 100) : 0;
        $monthlyUnresolvedPercent = $monthlyTotal > 0 ? 100 - $monthlyResolvedPercent : 0;

        // Month dropdown: Jan–Dec of the selected year
        $monthOptions = [];
        for ($i = 1; $i <= 12; $i++) {
            $value = sprintf('%04d-%02d', $selectedYear, $i);
            $monthOptions[$value] = \Carbon\Carbon::create()->month($i)->format('F');
        }

        // Year dropdown: earliest ticket year through current year, newest first
        $earliestYear = Ticket::min('created_at')
            ? \Carbon\Carbon::parse(Ticket::min('created_at'))->year
            : now()->year;
    $yearOptions = range(now()->year, now()->year - 2);

        // Month-scoped Avg Response / Resolution time (for the filterable metric cards)
        $avgResponseHoursMonth = Ticket::whereNotNull('first_response_at')
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->get()
            ->avg(fn ($t) => BusinessHours::diffInHours($t->created_at, $t->first_response_at));

        $avgResolutionHoursMonth = Ticket::whereNotNull('resolved_at')
            ->whereNotNull('first_response_at')
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->get()
            ->avg(fn ($t) => BusinessHours::diffInHours($t->first_response_at, $t->resolved_at));

        return view('technician.dashboard', compact(
            'tickets', 'openCount', 'resolvedThisMonth', 'totalIssues',
            'acknowledgedCount', 'resolvedCount', 'resolvedPercent',
            'avgResolutionHours', 'avgResponseHours',
            'avgResponseHoursMonth', 'avgResolutionHoursMonth',
            'categoryCounts', 'departmentCounts',
            'startDate', 'endDate', 'filteredTotal', 'filteredResolved', 'filteredOpen',
            'recentTickets',
            'selectedMonth', 'selectedMonthNum', 'selectedYear', 'selectedMonthLabel',
            'monthOptions', 'yearOptions',
            'monthlyTotal', 'monthlyResolved', 'monthlyUnresolved',
            'monthlyResolvedPercent', 'monthlyUnresolvedPercent'
        ));
    }

    public function allTickets(Request $request)
    {
        $query = Ticket::query()->with('submittedBy');

        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

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