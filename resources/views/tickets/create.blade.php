<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Submit a Ticket
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">

                @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('tickets.store') }}" class="space-y-8">
                    @csrf

                    {{-- Category --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-3">Category</label>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach (['Hardware' => '🖥️', 'Software' => '🧩', 'Network' => '📶', 'Printer' => '🖨️', 'Internet' => '🌐', 'Others' => '⋯'] as $cat => $icon)
                            <label class="flex flex-col items-center gap-2 border-2 rounded-lg p-4 cursor-pointer hover:border-gray-400 has-[:checked]:border-amber-600 has-[:checked]:bg-amber-50">
                                <input type="radio" name="category" value="{{ $cat }}" class="hidden"
                                    {{ old('category') === $cat ? 'checked' : '' }} required>
                                <span class="text-xl">{{ $icon }}</span>
                                <span class="text-sm font-semibold text-gray-700">{{ $cat }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                   {{-- Priority --}}
<div>
    <label class="block text-sm font-bold text-gray-800 mb-3">Priority</label>
    <div class="flex flex-wrap gap-3">
        <label class="flex items-center gap-2 border-2 rounded-full px-5 py-2.5 cursor-pointer hover:border-gray-400 has-[:checked]:border-green-600 has-[:checked]:bg-green-50">
            <input type="radio" name="priority" value="Low" class="hidden" {{ old('priority', 'Medium') === 'Low' ? 'checked' : '' }} required>
            <span class="w-2 h-2 rounded-full bg-green-600"></span>
            <span class="text-sm font-bold text-gray-700">Low</span>
        </label>
        <label class="flex items-center gap-2 border-2 rounded-full px-5 py-2.5 cursor-pointer hover:border-gray-400 has-[:checked]:border-amber-600 has-[:checked]:bg-amber-50">
            <input type="radio" name="priority" value="Medium" class="hidden" {{ old('priority', 'Medium') === 'Medium' ? 'checked' : '' }} required>
            <span class="w-2 h-2 rounded-full bg-amber-600"></span>
            <span class="text-sm font-bold text-gray-700">Medium</span>
        </label>
        <label class="flex items-center gap-2 border-2 rounded-full px-5 py-2.5 cursor-pointer hover:border-gray-400 has-[:checked]:border-orange-600 has-[:checked]:bg-orange-50">
            <input type="radio" name="priority" value="High" class="hidden" {{ old('priority', 'Medium') === 'High' ? 'checked' : '' }} required>
            <span class="w-2 h-2 rounded-full bg-orange-600"></span>
            <span class="text-sm font-bold text-gray-700">High</span>
        </label>
        <label class="flex items-center gap-2 border-2 rounded-full px-5 py-2.5 cursor-pointer hover:border-gray-400 has-[:checked]:border-red-600 has-[:checked]:bg-red-50">
            <input type="radio" name="priority" value="Critical" class="hidden" {{ old('priority', 'Medium') === 'Critical' ? 'checked' : '' }} required>
            <span class="w-2 h-2 rounded-full bg-red-600"></span>
            <span class="text-sm font-bold text-gray-700">Critical</span>
        </label>
    </div>
    <p class="text-sm text-gray-500 mt-2">Critical: production line or point-of-sale is down. Choose this only if work has stopped.</p>
</div>

                    {{-- Subject --}}
                    <div>
                        <label for="subject" class="block text-sm font-bold text-gray-800 mb-2">Subject</label>
                        <input type="text" name="subject" id="subject" value="{{ old('subject') }}"
                            placeholder="e.g. Label printer misaligning on batch runs"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-amber-500 focus:ring-amber-500" required>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-bold text-gray-800 mb-2">Description</label>
                        <textarea name="description" id="description" rows="5"
                            placeholder="What happened, when it started, and what you've already tried."
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-amber-500 focus:ring-amber-500" required>{{ old('description') }}</textarea>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-3">
                        <button type="submit"
                            class="bg-gray-900 text-white font-bold px-6 py-3 rounded-md hover:bg-gray-700">
                            Submit ticket
                        </button>
                        <a href="{{ route('tickets.index') }}"
                            class="border-2 border-gray-300 text-gray-600 font-bold px-6 py-3 rounded-md hover:border-gray-400">
                            Cancel
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>