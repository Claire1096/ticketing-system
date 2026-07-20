<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-700">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

          {{-- Month filter (left) + Download report with date filter (right) --}}
<div class="flex justify-between items-end">
    <form method="GET" id="dashboardMonthForm" class="flex items-end gap-2">
        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Month</label>
            <select
                name="month"
                onchange="this.form.submit()"
                class="text-sm border-gray-300 rounded-full shadow-sm bg-white font-semibold px-4 py-2.5">
                @foreach ($monthOptions as $value => $label)
                    <option value="{{ $value }}" {{ $selectedMonth === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Year</label>
            <select
                name="year"
                onchange="this.form.submit()"
                class="text-sm border-gray-300 rounded-full shadow-sm bg-white font-semibold px-4 py-2.5">
                @foreach ($yearOptions as $year)
                    <option value="{{ $year }}" {{ $selectedYear === $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    <form method="GET" action="{{ route('technician.kpi-report') }}" class="flex items-end gap-2 bg-white border border-gray-200 rounded-lg p-3">
        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">From</label>
            <input type="date" name="start_date" class="text-sm border-gray-300 rounded-md">
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">To</label>
            <input type="date" name="end_date" class="text-sm border-gray-300 rounded-md">
        </div>
        <button type="submit"
            class="inline-flex items-center gap-2 bg-pink-700 hover:bg-pink-800 text-white font-bold px-5 py-2.5 rounded-md text-sm transition">
            Download Report
        </button>
    </form>
</div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

                {{-- LEFT: 2/3 width --}}
                <div class="lg:col-span-2 space-y-6">
<div class="mb-4">
    <h3 class="text-lg font-bold text-gray-800">
        Dashboard Metrics
    </h3>
</div>
                    {{-- Two big donut stats --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-[#F5E9F7] rounded-xl p-6 flex items-center gap-5">
                            <div class="relative w-24 h-24 rounded-full flex items-center justify-center flex-shrink-0"
                                style="background: conic-gradient(#F472B6 0% {{ $totalIssues > 0 ? round(($openCount/$totalIssues)*100) : 0 }}%, #A78BFA {{ $totalIssues > 0 ? round(($openCount/$totalIssues)*100) : 0 }}% 100%);">
                                <div class="w-16 h-16 rounded-full bg-[#F5E9F7] flex items-center justify-center font-extrabold text-gray-800 text-xl">
                                    {{ $totalIssues }}
                                </div>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-gray-500 uppercase mb-1">Total Issues</div>
                                <div class="flex flex-col gap-1 text-[11px] text-gray-500">
                                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[#F472B6]"></span>Open ({{ $openCount }})</span>
                                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[#A78BFA]"></span>Other ({{ $totalIssues - $openCount }})</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-[#F5E9F7] rounded-xl p-6 flex items-center gap-5">
                            <div class="relative w-24 h-24 rounded-full flex items-center justify-center flex-shrink-0"
                                style="background: conic-gradient(#34D399 0% {{ $resolvedPercent }}%, #FDE68A {{ $resolvedPercent }}% 100%);">
                                <div class="w-16 h-16 rounded-full bg-[#F5E9F7] flex items-center justify-center font-extrabold text-gray-800 text-xl">
                                    {{ $resolvedCount }}
                                </div>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-gray-500 uppercase mb-1">Resolved</div>
                                <div class="flex flex-col gap-1 text-[11px] text-gray-500">
                                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[#34D399]"></span>{{ $resolvedPercent }}% of total</span>
                                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[#FDE68A]"></span>Pending ({{ $totalIssues - $resolvedCount }})</span>
                                </div>
                            </div>
                        </div>
                    </div>


<div class="text-sm font-bold text-gray-700">
    Performance Metrics
</div>
{{-- 3 small metric cards --}}
<div class="grid grid-cols-3 gap-4">
                       <div class="bg-white border border-[#F3D9F0] rounded-xl p-5">
    <div class="text-[11px] font-bold text-gray-500 uppercase mb-2">Avg. Response Time</div>
    <div class="text-xl font-extrabold text-gray-800">
        @if (is_null($avgResponseHoursMonth))
            —
        @elseif ($avgResponseHoursMonth < 1)
            {{ round($avgResponseHoursMonth * 60) }} <span class="text-xs font-medium text-gray-400">min</span>
        @else
            {{ number_format($avgResponseHoursMonth, 1) }} <span class="text-xs font-medium text-gray-400">hrs</span>
        @endif
    </div>
</div>
<div class="bg-white border border-[#F3D9F0] rounded-xl p-5">
    <div class="text-[11px] font-bold text-gray-500 uppercase mb-2">Avg. Resolution Time</div>
    <div class="text-xl font-extrabold text-gray-800">
        @if (is_null($avgResolutionHoursMonth))
            —
        @elseif ($avgResolutionHoursMonth < 1)
            {{ round($avgResolutionHoursMonth * 60) }} <span class="text-xs font-medium text-gray-400">min</span>
        @else
            {{ number_format($avgResolutionHoursMonth, 1) }} <span class="text-xs font-medium text-gray-400">hrs</span>
        @endif
    </div>
</div>
<div class="bg-white border border-[#F3D9F0] rounded-xl p-5">
    <div class="text-[11px] font-bold text-gray-500 uppercase mb-2">Resolved ({{ $selectedMonthLabel }})</div>
    <div class="text-xl font-extrabold text-green-600">
        {{ $monthlyResolved }} <span class="text-xs font-medium text-green-400">tickets</span>
    </div>
</div>
</div>

                    {{-- Ticket by Category / Ticket by Department, side by side --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-white border border-[#F3D9F0] rounded-xl p-6">
                            <div class="font-bold text-gray-800 mb-4 text-sm">Tickets by Category</div>
                            @php $totalCat = $categoryCounts->sum(); @endphp
                            @if ($totalCat === 0)
                                <div class="text-sm text-gray-500">No data yet.</div>
                            @else
                                <div class="space-y-2.5">
                                    @foreach (['Hardware', 'Software', 'Network', 'Printer', 'Internet', 'Others'] as $cat)
                                        @php
                                            $count = $categoryCounts[$cat] ?? 0;
                                            $percent = $totalCat > 0 ? round(($count / $totalCat) * 100) : 0;
                                        @endphp
                                        <div>
                                            <div class="flex justify-between text-xs mb-1">
                                                <span class="font-semibold text-gray-700">{{ $cat }}</span>
                                                <span class="text-gray-500">{{ $count }}</span>
                                            </div>
                                            <div class="w-full bg-[#F5E9F7] rounded-full h-1.5">
                                                <div class="h-1.5 rounded-full" style="width: {{ $percent }}%; background:#EC4899;"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="bg-white border border-[#F3D9F0] rounded-xl p-6">
                            <div class="font-bold text-gray-800 mb-4 text-sm">Tickets by Department</div>
                            @php $totalDept = $departmentCounts->sum(); @endphp
                            @if ($totalDept === 0)
                                <div class="text-sm text-gray-500">No data yet.</div>
                            @else
                                <div class="space-y-2.5">
                                    @foreach (['Sales and Marketing', 'Logistics', 'Human Resource', 'Finance and Accounting', 'IT Dept', 'Production', 'Executives'] as $dept)
                                        @php
                                            $count = $departmentCounts[$dept] ?? 0;
                                            $percent = $totalDept > 0 ? round(($count / $totalDept) * 100) : 0;
                                        @endphp
                                        @if ($count > 0)
                                            <div>
                                                <div class="flex justify-between text-xs mb-1">
                                                    <span class="font-semibold text-gray-700">{{ $dept }}</span>
                                                    <span class="text-gray-500">{{ $count }}</span>
                                                </div>
                                                <div class="w-full bg-[#EEF2FF] rounded-full h-1.5">
                                                    <div class="h-1.5 rounded-full" style="width: {{ $percent }}%; background:#6366F1;"></div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- RIGHT: 1/3 width --}}
                <div class="lg:col-span-1 space-y-6">

                    {{-- Recent Tickets --}}
                    <div class="bg-white border border-[#F3D9F0] rounded-xl overflow-hidden shadow-sm">
                        <div class="px-5 py-3 border-b border-[#F3D9F0] font-bold text-gray-800 bg-[#FBF0FA] text-sm">
                            Recent Tickets
                        </div>
                        <div class="divide-y divide-[#F3D9F0]">
                            @forelse ($recentTickets as $ticket)
                                <a href="{{ route('tickets.show', $ticket) }}" class="block px-5 py-3 hover:bg-[#FBF0FA]">
                                    <div class="text-sm font-semibold text-gray-800 truncate">
                                        {{ Str::limit($ticket->description, 35) }}
                                    </div>
                                    <div class="flex justify-between items-center mt-1">
                                        <span class="text-xs text-gray-500">{{ $ticket->submittedBy->name }}</span>
                                        <span class="text-xs text-gray-400">{{ $ticket->created_at->format('M j') }}</span>
                                    </div>
                                </a>
                            @empty
                                <div class="px-5 py-6 text-center text-sm text-gray-400">No tickets yet.</div>
                            @endforelse
                        </div>
                        <div class="px-5 py-3 text-right bg-[#FBF0FA]">
                            <a href="{{ route('tickets.all') }}" class="text-xs font-bold" style="color:#C026A3;">View all →</a>
                        </div>
                    </div>

                    <div class="bg-pink-100 rounded-xl p-6">
    <div class="flex justify-between items-center mb-4">
        <div class="text-base font-bold text-gray-800">Monthly Ticket History</div>
       <div class="text-sm font-semibold text-gray-600">
    {{ $selectedMonthLabel }}
</div>
    </div>

    <div class="bg-[#E5E5E5] rounded-lg p-5">
        @if ($monthlyTotal === 0)
            <div class="text-center text-sm text-gray-500 py-8">
                No tickets in {{ $selectedMonthLabel }}.
            </div>
        @else
            <div class="flex items-center gap-5">
                <canvas id="monthlyPieChart" width="110" height="110" style="width:110px; height:110px; flex-shrink:0;"></canvas>
                <div class="flex flex-col gap-2.5 text-sm">
                    <span class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full flex-shrink-0" style="background:#818CF8;"></span>
                        Resolved ({{ $monthlyResolved }})
                    </span>
                    <span class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full flex-shrink-0" style="background:#F472B6;"></span>
                        Unresolved ({{ $monthlyUnresolved }})
                    </span>
                </div>
            </div>
        @endif
    </div>
</div>

    @if ($monthlyTotal > 0)
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
        <script>
            new Chart(document.getElementById('monthlyPieChart'), {
                type: 'pie',
                data: {
                    labels: [
                        'Resolved ({{ $monthlyResolved }} · {{ $monthlyResolvedPercent }}%)',
                        'Unresolved ({{ $monthlyUnresolved }} · {{ $monthlyUnresolvedPercent }}%)'
                    ],
                    datasets: [{
                        data: [{{ $monthlyResolved }}, {{ $monthlyUnresolved }}],
                        backgroundColor: ['#818CF8', '#F472B6'],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: true }
                    }
                }
            });
        </script>
    @endif

                </div>
                {{-- /RIGHT --}}

            </div>
            {{-- /grid grid-cols-1 lg:grid-cols-3 --}}

        </div>
        {{-- /max-w-7xl --}}
    </div>
    {{-- /py-8 --}}
</x-app-layout>