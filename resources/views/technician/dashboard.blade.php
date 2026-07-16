<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-700">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Download report button --}}
            <div class="flex justify-end">
                <a href="{{ route('technician.kpi-report') }}"
                    class="inline-flex items-center gap-2 bg-pink-700 hover:bg-pink-800 text-white font-bold px-5 py-2.5 rounded-md text-sm transition">
                    Download Report
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

                {{-- LEFT: 2/3 width --}}
                <div class="lg:col-span-2 space-y-6">

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

                    {{-- 3 small metric cards --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-white border border-[#F3D9F0] rounded-xl p-5">
                            <div class="text-[11px] font-bold text-gray-500 uppercase mb-2">Avg. Response Time</div>
                            <div class="text-xl font-extrabold text-gray-800">
                                @if (is_null($avgResponseHours))
                                    —
                                @elseif ($avgResponseHours < 1)
                                    {{ round($avgResponseHours * 60) }} <span class="text-xs font-medium text-gray-400">min</span>
                                @else
                                    {{ number_format($avgResponseHours, 1) }} <span class="text-xs font-medium text-gray-400">hrs</span>
                                @endif
                            </div>
                        </div>
                        <div class="bg-white border border-[#F3D9F0] rounded-xl p-5">
                            <div class="text-[11px] font-bold text-gray-500 uppercase mb-2">Avg. Resolution Time</div>
                            <div class="text-xl font-extrabold text-gray-800">
                                @if (is_null($avgResolutionHours))
                                    —
                                @elseif ($avgResolutionHours < 1)
                                    {{ round($avgResolutionHours * 60) }} <span class="text-xs font-medium text-gray-400">min</span>
                                @else
                                    {{ number_format($avgResolutionHours, 1) }} <span class="text-xs font-medium text-gray-400">hrs</span>
                                @endif
                            </div>
                        </div>
                        <div class="bg-white border border-[#F3D9F0] rounded-xl p-5">
                            <div class="text-[11px] font-bold text-gray-500 uppercase mb-2">Resolved This Month</div>
                            <div class="text-xl font-extrabold text-green-600">
                                {{ $resolvedThisMonth }} <span class="text-xs font-medium text-green-400">tickets</span>
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
        <form method="GET" id="monthFilterForm">
            <select name="month" onchange="document.getElementById('monthFilterForm').submit()"
                class="text-sm border-pink-300 rounded-md shadow-sm bg-gray-200 font-semibold px-2 py-1">
                @foreach ($monthOptions as $value => $label)
                    <option value="{{ $value }}" {{ $selectedMonth === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="bg-[#E5E5E5] rounded-lg p-5">
        @if ($monthlyTotal === 0)
            <div class="text-center text-sm text-gray-500 py-8">
                No tickets in {{ $monthOptions[$selectedMonth] }}.
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
</x-app-layout>