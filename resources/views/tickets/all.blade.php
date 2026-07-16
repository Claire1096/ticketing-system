<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-700 leading-tight">
            All Tickets
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if ($tickets->isEmpty())
                <div class="bg-white border border-gray-200 rounded-lg p-10 text-center text-gray-500">
                    No tickets have been submitted yet.
                </div>
            @else
<!-- Month and Year Filter Bar -->
<div class="bg-white p-4 rounded-lg shadow mb-6 flex justify-end">
    <form action="{{ route('tickets.all') }}" method="GET" class="flex items-center gap-3">
        
        <!-- Month Filter -->
        <div class="flex items-center gap-2">
            <label for="month" class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Month:</label>
            <select name="month" id="month" onchange="this.form.submit()" class="text-sm rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                <option value="">All Months</option>
                <option value="01" {{ request('month') == '01' ? 'selected' : '' }}>January</option>
                <option value="02" {{ request('month') == '02' ? 'selected' : '' }}>February</option>
                <option value="03" {{ request('month') == '03' ? 'selected' : '' }}>March</option>
                <option value="04" {{ request('month') == '04' ? 'selected' : '' }}>April</option>
                <option value="05" {{ request('month') == '05' ? 'selected' : '' }}>May</option>
                <option value="06" {{ request('month') == '06' ? 'selected' : '' }}>June</option>
                <option value="07" {{ request('month') == '07' ? 'selected' : '' }}>July</option>
                <option value="08" {{ request('month') == '08' ? 'selected' : '' }}>August</option>
                <option value="09" {{ request('month') == '09' ? 'selected' : '' }}>September</option>
                <option value="10" {{ request('month') == '10' ? 'selected' : '' }}>October</option>
                <option value="11" {{ request('month') == '11' ? 'selected' : '' }}>November</option>
                <option value="12" {{ request('month') == '12' ? 'selected' : '' }}>December</option>
            </select>
        </div>

        <!-- Year Filter -->
        <div class="flex items-center gap-2">
            <label for="year" class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Year:</label>
            <select name="year" id="year" onchange="this.form.submit()" class="text-sm rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                <option value="">All Years</option>
                <!-- Generates a dynamic range from 5 years ago up to next year -->
                @for ($y = date('Y') + 1; $y >= date('Y') - 5; $y--)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>
        </div>

        <!-- Clear Button (Shows up if either filter is active) -->
        @if(request()->filled('month') || request()->filled('year'))
            <a href="{{ route('tickets.all') }}" class="text-xs text-pink-600 hover:text-pink-700 underline font-medium ml-1">
                Clear Filters
            </a>
        @endif
    </form>
</div>
                <div class="space-y-3">
                    @foreach ($tickets as $ticket)
                        <a href="{{ route('tickets.show', $ticket) }}"
                            class="block bg-white border border-gray-200 rounded-lg p-5 hover:border-pink-400 hover:shadow-sm transition">
                            <div class="flex items-center gap-5">

                                <div class="text-xs text-gray-400 w-16 flex-shrink-0">
                                    #{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="font-bold text-gray-800 mb-1">{{ Str::limit($ticket->description, 60) }}</div>
                                    <div class="text-xs text-gray-500 mb-1">
                                        Submitted by {{ $ticket->submittedBy->name }}
                                    </div>
                                    <div class="flex gap-2">
                                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-gray-100 text-gray-600">
                                            {{ $ticket->category }}
                                        </span>
                                        @if ($ticket->department)
                                            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-blue-50 text-blue-600">
                                                {{ $ticket->department }}
                                            </span>
                                        @endif
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
                                        'Resolved' => 'bg-green-500',
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