<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                My Tickets
            </h2>
            <a href="{{ route('tickets.create') }}"
                class="bg-pink-700 text-white font-bold px-5 py-2.5 rounded-md hover:bg-gray-700 text-sm">
                + New ticket
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if ($tickets->isEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-10 text-center text-gray-500">
                    You haven't submitted any tickets yet.
                    <a href="{{ route('tickets.create') }}" class="text-amber-700 font-semibold underline">
                        Submit your first one
                    </a>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($tickets as $ticket)
                        <a href="{{ route('tickets.show', $ticket) }}"
                            class="block bg-[#faf5f2] border border-gray-400 rounded-lg p-5 hover:border-pink-500 hover:shadow-lg transition">
                            <div class="flex items-center gap-5">

                                <div class="text-xs text-gray-400 w-16 flex-shrink-0">
                                    #{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="font-bold text-gray-800 mb-1">{{ $ticket->subject }}</div>
                                    <div class="flex gap-2">
                                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-gray-100 text-gray-600">
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
                                        <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $priorityColors[$ticket->priority] }}">
                                            {{ $ticket->priority }}
                                        </span>
                                    </div>
                                </div>

                                @php
                                    $statusColors = [
                                        'Open' => 'bg-blue-500',
                                        'In Progress' => 'bg-amber-500',
                                        'Pending' => 'bg-orange-400',
                                        'Resolved' => 'bg-green-400',
                                        'Closed' => 'bg-gray-400',
                                    ];
                                @endphp
                                <div class="w-14 h-14 rounded-full {{ $statusColors[$ticket->status] }} flex items-center justify-center text-white text-[10px] font-bold text-center leading-tight flex-shrink-0">
                                    {{ $ticket->status }}
                                </div>

                                <div class="text-xs text-gray-400 w-20 text-right flex-shrink-0">
                                    {{ $ticket->created_at->format('M j') }}
                                </div>

                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>