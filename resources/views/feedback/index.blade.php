<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-pink-700 leading-tight">
            Employee Feedback
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if ($feedbacks->isEmpty())
                <div class="bg-white border border-gray-200 rounded-lg p-10 text-center text-gray-500">
                    No feedback submitted yet.
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($feedbacks as $feedback)
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <div class="font-bold text-gray-800">{{ $feedback->submittedBy->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $feedback->created_at->format('M j, Y g:i A') }}</div>
                                    @if ($feedback->ticket_id)
                                        <div class="text-xs text-gray-400">Ticket #{{ str_pad($feedback->ticket_id, 4, '0', STR_PAD_LEFT) }}</div>
                                    @endif
                                </div>
                                @if ($feedback->rating)
                                    <div class="text-amber-400 text-lg">
                                        {{ str_repeat('★', $feedback->rating) }}{{ str_repeat('☆', 5 - $feedback->rating) }}
                                    </div>
                                @endif
                            </div>
                            <p class="text-gray-700 text-sm leading-relaxed">{{ $feedback->message }}</p>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>