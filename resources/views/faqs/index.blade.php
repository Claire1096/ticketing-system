<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Knowledge Base
        </h2>
    </x-slot>

    <div class="py-8">
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
                    class="w-full max-w-md border-gray-300 rounded-md shadow-sm text-sm">
            </form>

            {{-- Category filter chips --}}
            <div class="flex flex-wrap gap-2 mb-8">
                <a href="{{ route('faqs.index', ['search' => $search]) }}"
                    class="text-xs font-bold px-4 py-2 rounded-full {{ !$category ? 'bg-gray-900 text-white' : 'bg-white border border-gray-200 text-gray-600' }}">
                    All
                </a>
                @foreach (['Hardware', 'Software', 'Network', 'Printer', 'Internet', 'Others'] as $cat)
                    <a href="{{ route('faqs.index', ['category' => $cat, 'search' => $search]) }}"
                        class="text-xs font-bold px-4 py-2 rounded-full {{ $category === $cat ? 'bg-gray-900 text-white' : 'bg-white border border-gray-200 text-gray-600' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>

            {{-- FAQ list --}}
            @if ($faqs->isEmpty())
                <div class="bg-white border border-gray-200 rounded-lg p-10 text-center text-gray-500">
                    No articles found{{ $search ? ' for "' . $search . '"' : '' }}.
                </div>
            @else
                <div class="bg-white border border-gray-200 rounded-lg divide-y divide-gray-100">
                    @foreach ($faqs as $faq)
                        <div x-data="{ open: false }" class="px-6">
                            <button @click="open = !open" class="w-full flex justify-between items-center gap-4 py-5 text-left">
                                <span class="font-bold text-gray-800">{{ $faq->question }}</span>
                                <span class="text-amber-700 text-xl flex-shrink-0" x-text="open ? '−' : '+'"></span>
                            </button>
                            <div x-show="open" x-collapse class="pb-5 text-sm text-gray-600 leading-relaxed">
                                {{ $faq->answer }}
                                <div class="mt-3">
                                    <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-gray-100 text-gray-500">
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