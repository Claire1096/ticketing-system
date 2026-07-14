<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-gray-400 rounded-2xl p-10 flex items-center justify-between gap-8 overflow-hidden relative">

                <div class="max-w-lg relative z-10">
                    <h1 class="text-3xl font-bold text-gray-700 mb-4">
                        Welcome, {{ auth()->user()->name }}!
                    </h1>
                    <p class="text-gray-500 mb-6 leading-relaxed">
                        Need help with something? Submit an IT ticket and our team will get right on it —
                        or check the Knowledge Base for a quick fix.
                    </p>
                    <div class="flex gap-3">
                        <a href="{{ route('tickets.create') }}"
                            class="inline-flex items-center gap-2 bg-pink-600 hover:bg-pink-700 text-white font-bold px-6 py-3 rounded-full transition">
                            Submit New Ticket
                            <span class="text-lg leading-none">+</span>
                        </a>
                        <a href="{{ route('tickets.index') }}"
                            class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-600 font-bold px-6 py-3 rounded-full border border-gray-200 transition">
                            My Tickets
                        </a>
                    </div>
                </div>

                {{-- Simple decorative illustration --}}
                <div class="hidden md:block flex-shrink-0 relative z-10">
                    <svg width="220" height="160" viewBox="0 0 220 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="20" y="10" width="160" height="110" rx="10" fill="#F472B6" opacity="0.9"/>
                        <rect x="32" y="22" width="136" height="70" rx="4" fill="#FCE7F3"/>
                        <rect x="42" y="32" width="55" height="20" rx="3" fill="#F9A8D4"/>
                        <rect x="42" y="58" width="80" height="8" rx="2" fill="#F9A8D4"/>
                        <rect x="42" y="70" width="60" height="8" rx="2" fill="#FBCFE8"/>
                        <rect x="86" y="120" width="28" height="8" rx="2" fill="#DB2777"/>
                        <rect x="70" y="128" width="60" height="8" rx="4" fill="#DB2777"/>
                        <circle cx="185" cy="45" r="30" fill="#FBCFE8" opacity="0.6"/>
                    </svg>
                </div>

                {{-- Background circle accent --}}
                <div class="absolute -right-10 -bottom-10 w-56 h-56 bg-pink-100 rounded-full opacity-50"></div>
            </div>

        </div>
    </div>
</x-app-layout>