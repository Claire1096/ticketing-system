<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Ticket #{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}
            </h2>
           <div class="flex gap-4 items-center">
    @if (auth()->user()->role === 'technician')
        <a href="{{ route('tickets.edit', $ticket) }}" class="text-sm text-amber-700 font-semibold hover:underline">
            Update ticket
        </a>
    @else
        <form method="POST" action="{{ route('tickets.destroy', $ticket) }}"
            onsubmit="return confirm('Delete this ticket? This cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-sm text-red-600 font-semibold hover:underline">
                Delete ticket
            </button>
        </form>
    @endif
    <a href="{{ route('tickets.index') }}" class="text-sm text-gray-500 hover:text-gray-800">
        &larr; Back to my tickets
    </a>
</div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-8">

                {{-- Top row: subject + status seal --}}
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ Str::limit($ticket->description, 80) }}</h3>
                        <div class="flex gap-2">
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-gray-100 text-gray-600">
                                {{ $ticket->category }}
                            </span>
                             <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-blue-50 text-blue-600">
        {{ $ticket->department }}
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
                            'Resolved' => 'bg-green-500',
                            'Closed' => 'bg-gray-400',
                        ];
                    @endphp
                    <div class="w-16 h-16 rounded-full {{ $statusColors[$ticket->status] }} flex items-center justify-center text-white text-[10px] font-bold text-center leading-tight flex-shrink-0">
                        {{ $ticket->status }}
                    </div>
                </div>

                <hr class="my-6 border-gray-200">

                {{-- Description --}}
                <div class="mb-6">
                    <div class="text-xs font-bold text-gray-500 uppercase mb-2">Description</div>
                    <p class="text-gray-700 whitespace-pre-line">{{ $ticket->description }}</p>
                </div>

                {{-- Meta info --}}
                <div class="grid grid-cols-2 gap-6 mb-6 text-sm">
                    <div>
                        <div class="text-xs font-bold text-gray-500 uppercase mb-1">Submitted by</div>
                        <div class="text-gray-700">{{ $ticket->submittedBy->name }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-gray-500 uppercase mb-1">Assigned to</div>
                        <div class="text-gray-700">
                            {{ $ticket->assignedTo->name ?? 'Not yet assigned' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-gray-500 uppercase mb-1">Submitted on</div>
                        <div class="text-gray-700">{{ $ticket->created_at->format('M j, Y g:i A') }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-gray-500 uppercase mb-1">Resolved on</div>
                        <div class="text-gray-700">
                            {{ $ticket->resolved_at?->format('M j, Y g:i A') ?? '—' }}
                        </div>
                    </div>
                </div>

                {{-- Technician remarks --}}
                @if ($ticket->technician_remarks)
                    <hr class="my-6 border-gray-200">
                    <div>
                        <div class="text-xs font-bold text-gray-500 uppercase mb-2">Technician remarks</div>
                        <p class="text-gray-700 bg-gray-50 border border-gray-200 rounded-md p-4 whitespace-pre-line">
                            {{ $ticket->technician_remarks }}
                        </p>
                    </div>
                @endif
@if ($ticket->status === 'Resolved' && $ticket->submitted_by === auth()->id())
    @php
        $existingFeedback = \App\Models\Feedback::where('ticket_id', $ticket->id)
            ->where('submitted_by', auth()->id())
            ->first();
    @endphp

    <hr class="my-6 border-gray-200">

    @if ($existingFeedback)
        <div class="bg-green-50 border border-green-200 rounded-lg p-5">
            <div class="text-sm font-bold text-green-800 mb-2">Thanks for your feedback!</div>
            @if ($existingFeedback->rating)
                <div class="text-amber-400 text-lg mb-2">
                    {{ str_repeat('★', $existingFeedback->rating) }}{{ str_repeat('☆', 5 - $existingFeedback->rating) }}
                </div>
            @endif
            <p class="text-sm text-gray-700">{{ $existingFeedback->message }}</p>
        </div>
    @else
        <div class="bg-pink-50 border border-pink-100 rounded-lg p-5">
            <div class="text-sm font-bold text-gray-800 mb-3">How was this resolved for you?</div>

            <form method="POST" action="{{ route('feedback.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

                <div x-data="{ rating: 0 }">
                    <input type="hidden" name="rating" x-model="rating">
                    <div class="flex gap-1">
                        <template x-for="star in [1,2,3,4,5]" :key="star">
                            <button type="button" @click="rating = star"
                                class="text-2xl"
                                :class="star <= rating ? 'text-amber-400' : 'text-gray-300'">
                                ★
                            </button>
                        </template>
                    </div>
                </div>

                <textarea name="message" rows="3"
                    placeholder="Any comments about how this was handled? (optional)"
                    class="w-full border-gray-300 rounded-md shadow-sm text-sm"></textarea>

                <button type="submit"
                    class="bg-pink-700 hover:bg-pink-800 text-white font-bold px-5 py-2.5 rounded-md text-sm">
                    Submit feedback
                </button>
            </form>
        </div>
    @endif
@endif
            </div>
        </div>
    </div>
</x-app-layout>