<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-pink-700 leading-tight">
                Knowledge Base
            </h2>
            @if (auth()->user()->role === 'technician')
                <a href="{{ route('faqs.create') }}"
                    class="bg-pink-700 hover:bg-pink-800 text-white font-bold px-5 py-2.5 rounded-md text-sm">
                    + Add Article
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        ...
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <p class="text-gray-600 text-sm mb-6">
                Common fixes the team has written down, so you don't have to wait on a ticket.
            </p>

            {{-- Search --}}
            <form method="GET" action="{{ route('faqs.index') }}" class="mb-6">
                @if ($category)
                    <input type="hidden" name="category" value="{{ $category }}">
                @endif
                <input type="text" name="search" value="{{ $search }}"
                    placeholder="Search articles, e.g. &quot;printer offline&quot;"
                    class="w-full max-w-md border-gray-300 rounded-md shadow-sm text-sm focus:border-pink-500 focus:ring-pink-500">
            </form>

            {{-- Category filter chips --}}
            <div class="flex flex-wrap gap-2 mb-8">
                <a href="{{ route('faqs.index', ['search' => $search]) }}"
                    class="text-xs font-bold px-4 py-2 rounded-full {{ !$category ? 'bg-pink-700 text-white' : 'bg-white border border-gray-200 text-gray-600' }}">
                    All
                </a>
                @foreach (['Hardware', 'Software', 'Network', 'Printer', 'Internet', 'Others'] as $cat)
                    <a href="{{ route('faqs.index', ['category' => $cat, 'search' => $search]) }}"
                        class="text-xs font-bold px-4 py-2 rounded-full {{ $category === $cat ? 'bg-pink-700 text-white' : 'bg-white border border-gray-200 text-gray-600' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>

            {{-- FAQ list --}}
            @if ($faqs->isEmpty())
                <div class="bg-pink-100 border border-gray-200 rounded-lg p-10 text-center text-gray-500">
                    No articles found{{ $search ? ' for "' . $search . '"' : '' }}.
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($faqs as $faq)
                        <div x-data="{ open: false }" class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <button @click="open = !open"
                                class="w-full flex justify-between items-center gap-4 px-6 py-4 text-left hover:bg-pink-100">
                                <span class="font-bold text-gray-900 text-base">{{ $faq->question }}</span>
                                <span class="flex-shrink-0 w-7 h-7 rounded-full bg-pink-100 text-pink-700 font-bold text-lg flex items-center justify-center"
                                    x-text="open ? '−' : '+'"></span>
                            </button>
                            <div x-show="open" x-collapse class="px-6 pb-5 border-t border-gray-100 pt-4">
                                <p class="text-gray-800 text-sm leading-relaxed">{{ $faq->answer }}</p>
                                <div class="mt-3">
                                    <span class="text-xs font-bold px-3 py-1 rounded-full bg-gray-100 text-gray-700">
                                        {{ $faq->category }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>