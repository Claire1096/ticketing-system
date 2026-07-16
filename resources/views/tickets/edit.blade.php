<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Update Ticket #{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg p-8">

                <div class="mb-6">
                    <div class="font-bold text-gray-800 text-lg mb-1">{{ Str::limit($ticket->description, 80) }}</div>
                    <p class="text-gray-600 text-sm">{{ $ticket->description }}</p>
                </div>

                <hr class="my-6 border-gray-200">

                <form method="POST" action="{{ route('tickets.update', $ticket) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                  <select name="status" class="w-full border-gray-300 rounded-md shadow-sm">
    {{-- If Open, only show In Progress --}}
    @if ($ticket->status === 'Open')
        <option value="Open" selected>Open</option>
        <option value="In Progress">In Progress</option>

    {{-- If In Progress, only show Resolved --}}
    @elseif ($ticket->status === 'In Progress')
        <option value="In Progress" selected>In Progress</option>
        <option value="Resolved">Resolved</option>

    {{-- If Pending, allow moving to In Progress or Resolved --}}
    @elseif ($ticket->status === 'Pending')
        <option value="Pending" selected>Pending</option>
        <option value="In Progress">In Progress</option>
        <option value="Resolved">Resolved</option>

    {{-- Default for others (Resolved, Closed) --}}
    @else
        <option value="{{ $ticket->status }}" selected>{{ $ticket->status }}</option>
    @endif
</select>

                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2">Assign to</label>
                        <select name="assigned_to" class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Unassigned</option>
                            @foreach (\App\Models\User::where('role', 'technician')->get() as $tech)
                                <option value="{{ $tech->id }}" {{ $ticket->assigned_to === $tech->id ? 'selected' : '' }}>
                                    {{ $tech->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2">Technician remarks</label>
                        <textarea name="technician_remarks" rows="4"
                            class="w-full border-gray-300 rounded-md shadow-sm">{{ $ticket->technician_remarks }}</textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="bg-gray-900 text-white font-bold px-6 py-3 rounded-md hover:bg-gray-700">
                            Save changes
                        </button>
                        <a href="{{ route('tickets.show', $ticket) }}"
                            class="border-2 border-gray-300 text-gray-600 font-bold px-6 py-3 rounded-md hover:border-gray-400">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>