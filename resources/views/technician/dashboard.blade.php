<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Technician Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            {{-- KPI cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="bg-white border border-gray-200 rounded-lg p-5">
                    <div class="text-xs font-bold text-gray-500 uppercase mb-2">Open tickets</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $openCount }}</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-5">
                    <div class="text-xs font-bold text-gray-500 uppercase mb-2">Resolved this month</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $resolvedThisMonth }}</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-5">
                    <div class="text-xs font-bold text-gray-500 uppercase mb-2">Avg. resolution time</div>
                    <div class="text-3xl font-bold text-gray-800">
                        {{ $avgResolutionHours ? number_format($avgResolutionHours, 1) . ' hrs' : '—' }}
                    </div>
                </div>
            </div>

            {{-- All tickets --}}
            <div class="bg-white border border-gray-200 rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 font-bold text-gray-800">
                    All tickets
                </div>

                @if ($tickets->isEmpty())
                    <div class="p-10 text-center text-gray-500">
                        No tickets have been submitted yet.
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach ($tickets as $ticket)
                            <a href="{{ route('tickets.show', $ticket) }}"
                                class="flex items-center gap-5 px-6 py-4 hover:bg-gray-50">

                                <div class="text-xs text-gray-400 w-16 flex-shrink-0">
                                    #{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="font-bold text-gray-800 mb-1">{{ $ticket->subject }}</div>
                                    <div class="text-xs text-gray-500">
                                        Submitted by {{ $ticket->submittedBy->name }}
                                    </div>
                                </div>

                                <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 flex-shrink-0">
                                    {{ $ticket->category }}
                                </span>

                                @php
                                    $priorityColors = [
                                        'Low' => 'bg-green-100 text-green-700',
                                        'Medium' => 'bg-amber-100 text-amber-700',
                                        'High' => 'bg-orange-100 text-orange-700',
                                        'Critical' => 'bg-red-100 text-red-700',
                                    ];
                                @endphp
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $priorityColors[$ticket->priority] }} flex-shrink-0">
                                    {{ $ticket->priority }}
                                </span>

                                @php
                                    $statusColors = [
                                        'Open' => 'bg-blue-500',
                                        'In Progress' => 'bg-amber-500',
                                        'Pending' => 'bg-orange-400',
                                        'Resolved' => 'bg-green-500',
                                        'Closed' => 'bg-gray-400',
                                    ];
                                @endphp
                                <div class="w-12 h-12 rounded-full {{ $statusColors[$ticket->status] }} flex items-center justify-center text-white text-[9px] font-bold text-center leading-tight flex-shrink-0">
                                    {{ $ticket->status }}
                                </div>

                                <div class="text-xs text-gray-400 w-20 text-right flex-shrink-0">
                                    {{ $ticket->created_at->format('M j') }}
                                </div>

                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>