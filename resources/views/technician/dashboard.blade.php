<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-700">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- LEFT: donut stats + table --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Donut stat cards --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                        {{-- Total Issues --}}
                        <div class="bg-[#F5E9F7] rounded-xl p-5 flex flex-col items-center">
                            <div class="text-xs font-bold text-gray-500 uppercase mb-3 text-center">Total Issues</div>
                            <div class="relative w-20 h-20 rounded-full flex items-center justify-center"
                                style="background: conic-gradient(#F472B6 0% {{ $totalIssues > 0 ? round(($openCount/$totalIssues)*100) : 0 }}%, #A78BFA {{ $totalIssues > 0 ? round(($openCount/$totalIssues)*100) : 0 }}% 100%);">
                                <div class="w-14 h-14 rounded-full bg-[#F5E9F7] flex items-center justify-center font-extrabold text-gray-800 text-lg">
                                    {{ $totalIssues }}
                                </div>
                            </div>
                            <div class="flex gap-3 mt-3 text-[10px] text-gray-500">
                                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[#F472B6]"></span>Open</span>
                                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[#A78BFA]"></span>Other</span>
                            </div>
                        </div>

                        {{-- Acknowledged --}}
                        <div class="bg-[#F5E9F7] rounded-xl p-5 flex flex-col items-center">
                            <div class="text-xs font-bold text-gray-500 uppercase mb-3 text-center">Acknowledged</div>
                            <div class="relative w-20 h-20 rounded-full flex items-center justify-center"
                                style="background: conic-gradient(#818CF8 0% {{ $totalIssues > 0 ? round(($acknowledgedCount/$totalIssues)*100) : 0 }}%, #FBCFE8 {{ $totalIssues > 0 ? round(($acknowledgedCount/$totalIssues)*100) : 0 }}% 100%);">
                                <div class="w-14 h-14 rounded-full bg-[#F5E9F7] flex items-center justify-center font-extrabold text-gray-800 text-lg">
                                    {{ $acknowledgedCount }}
                                </div>
                            </div>
                            <div class="flex gap-3 mt-3 text-[10px] text-gray-500">
                                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[#818CF8]"></span>In Progress</span>
                            </div>
                        </div>

                        {{-- Resolved --}}
                        <div class="bg-[#F5E9F7] rounded-xl p-5 flex flex-col items-center">
                            <div class="text-xs font-bold text-gray-500 uppercase mb-3 text-center">Resolved</div>
                            <div class="relative w-20 h-20 rounded-full flex items-center justify-center"
                                style="background: conic-gradient(#34D399 0% {{ $resolvedPercent }}%, #FDE68A {{ $resolvedPercent }}% 100%);">
                                <div class="w-14 h-14 rounded-full bg-[#F5E9F7] flex items-center justify-center font-extrabold text-gray-800 text-lg">
                                    {{ $resolvedCount }}
                                </div>
                            </div>
                            <div class="flex gap-3 mt-3 text-[10px] text-gray-500">
                                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[#34D399]"></span>Resolved</span>
                                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[#FDE68A]"></span>Pending</span>
                            </div>
                        </div>

                    </div>

                    <!-- Cleaned Single Row Personal KPI Cards -->
                    <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; margin-bottom: 2rem;">
                        <!-- Avg Response Card -->
                        <div style="flex: 1; min-width: 200px; padding: 1.5rem; border: 1px solid #eef2f6; border-radius: 12px; background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
                            <h5 style="color: #888; font-size: 0.8rem; font-weight: bold; text-transform: uppercase; margin: 0 0 0.5rem 0; letter-spacing: 0.5px;">Avg. Response Time</h5>
                            <h2 style="margin: 0; font-size: 2rem; font-weight: 800; color: #2d3748;">
                                @if (is_null($avgResponseHours))
                                    —
                                @elseif ($avgResponseHours < 1)
                                    {{ round($avgResponseHours * 60) }} <span style="font-size: 1rem; font-weight: 500; color: #718096;">min</span>
                                @else
                                    {{ round($avgResponseHours, 1) }} <span style="font-size: 1rem; font-weight: 500; color: #718096;">hrs</span>
                                @endif
                            </h2>
                        </div>

                        <!-- Avg Resolution Card -->
                        <div style="flex: 1; min-width: 200px; padding: 1.5rem; border: 1px solid #eef2f6; border-radius: 12px; background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
                            <h5 style="color: #888; font-size: 0.8rem; font-weight: bold; text-transform: uppercase; margin: 0 0 0.5rem 0; letter-spacing: 0.5px;">Avg. Resolution Time</h5>
                            <h2 style="margin: 0; font-size: 2rem; font-weight: 800; color: #2d3748;">
                                @if (is_null($avgResolutionHours))
                                    —
                                @elseif ($avgResolutionHours < 1)
                                    {{ round($avgResolutionHours * 60) }} <span style="font-size: 1rem; font-weight: 500; color: #718096;">min</span>
                                @else
                                    {{ round($avgResolutionHours, 1) }} <span style="font-size: 1rem; font-weight: 500; color: #718096;">hrs</span>
                                @endif
                            </h2>
                        </div>

                        <!-- Resolved This Month Card -->
                        <div style="flex: 1; min-width: 200px; padding: 1.5rem; border: 1px solid #eef2f6; border-radius: 12px; background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
                            <h5 style="color: #888; font-size: 0.8rem; font-weight: bold; text-transform: uppercase; margin: 0 0 0.5rem 0; letter-spacing: 0.5px;">Resolved This Month</h5>
                            <h2 style="margin: 0; font-size: 2rem; font-weight: 800; color: #38a169;">
                                {{ $resolvedThisMonth }} <span style="font-size: 1rem; font-weight: 500; color: #48bb78;">tickets</span>
                            </h2>
                        </div>
                    </div>

                    {{-- Ticket table --}}
                    <div class="bg-white border border-[#F3D9F0] rounded-xl overflow-hidden">
                        <div class="px-6 py-4 border-b border-[#F3D9F0] font-bold text-gray-800 bg-[#FBF0FA]">
                            All Tickets
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-[#FBF0FA] text-left text-[11px] uppercase text-gray-500 font-bold">
                                        <th class="px-6 py-3">Name</th>
                                        <th class="px-6 py-3">Email</th>
                                        <th class="px-6 py-3">Issue</th>
                                        <th class="px-6 py-3">Date</th>
                                        <th class="px-6 py-3">ID</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#F3D9F0]">
                                    @forelse ($tickets->take(8) as $ticket)
                                        <tr onclick="window.location='{{ route('tickets.show', $ticket) }}'" class="cursor-pointer hover:bg-[#FBF0FA]">
                                            <td class="px-6 py-3 font-semibold text-gray-700">{{ $ticket->submittedBy->name }}</td>
                                            <td class="px-6 py-3 text-gray-500">{{ $ticket->submittedBy->email }}</td>
                                            <td class="px-6 py-3 text-gray-700">{{ Str::limit($ticket->description, 40) }}</td>
                                            <td class="px-6 py-3 text-gray-500">{{ $ticket->created_at->format('M j') }}</td>
                                            <td class="px-6 py-3 text-gray-400">#{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-8 text-center text-gray-400">No tickets yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="px-6 py-3 text-right bg-[#FBF0FA]">
                            <a href="{{ route('tickets.index') }}" class="text-sm font-bold" style="color:#C026A3;">View all →</a>
                        </div>
                    </div>

                </div>

                {{-- RIGHT: big resolved % + trend chart --}}
                <div class="bg-[#F9D9EE] rounded-xl p-6 flex flex-col">
                    <div class="text-xl font-extrabold text-gray-800 mb-4">{{ $resolvedPercent }}% Resolved</div>
                    <div class="bg-white rounded-xl p-4 flex-1">
                        <canvas id="trendChart" height="260"></canvas>
                    </div>
                </div>

            </div>

            {{-- Category breakdown --}}
            <div class="bg-white border border-[#F3D9F0] rounded-xl p-6">
                <div class="font-bold text-gray-800 mb-4">Tickets by Category</div>
                @php $totalCat = $categoryCounts->sum(); @endphp
                @if ($totalCat === 0)
                    <div class="text-sm text-gray-500">No tickets submitted yet.</div>
                @else
                    <div class="space-y-3">
                        @foreach (['Hardware', 'Software', 'Network', 'Printer', 'Internet', 'Others'] as $cat)
                            @php
                                $count = $categoryCounts[$cat] ?? 0;
                                $percent = $totalCat > 0 ? round(($count / $totalCat) * 100) : 0;
                            @endphp
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="font-semibold text-gray-700">{{ $cat }}</span>
                                    <span class="text-gray-500">{{ $count }} ({{ $percent }}%)</span>
                                </div>
                                <div class="w-full bg-[#F5E9F7] rounded-full h-2">
                                    <div class="h-2 rounded-full" style="width: {{ $percent }}%; background:#EC4899;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
        const ctx = document.getElementById('trendChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [
                    {
                        label: 'In Progress',
                        data: @json($acknowledgedData),
                        borderColor: '#818CF8',
                        backgroundColor: 'rgba(129,140,248,0.15)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 2,
                    },
                    {
                        label: 'Complaints',
                        data: @json($complaintsData),
                        borderColor: '#F472B6',
                        backgroundColor: 'rgba(244,114,182,0.15)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 2,
                    },
                    {
                        label: 'Resolved',
                        data: @json($resolvedData),
                        borderColor: '#34D399',
                        backgroundColor: 'rgba(52,211,153,0.15)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 2,
                    },
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    </script>
</x-app-layout>