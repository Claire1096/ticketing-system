<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- Logo & Nav Links Container (Takes up the left/center space) -->
            <div class="flex flex-1 justify-between sm:justify-start">
                <!-- Logo -->
                <div class="shrink-0 flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <img src="{{ asset('logo.svg') }}" alt="EM Power Beautiful Skin" class="h-11 w-auto">
                        <div class="text-left">
                            <div class="text-base font-bold text-gray-800 leading-tight">
                                <span class="text-pink-600">EM</span> Power Beautiful Skin
                            </div>
                            <div class="text-[10px] tracking-[0.2em] text-gray-500 font-semibold">CORPORATION</div>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links (With me-auto to push everything after it to the right) -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex sm:flex-1">
                    @if (auth()->user()->role === 'technician')
                        <x-nav-link :href="route('technician.dashboard')" :active="request()->routeIs('technician.dashboard')">
                            Dashboard
                        </x-nav-link>
                        <x-nav-link :href="route('tickets.all')" :active="request()->routeIs('tickets.all')">
                            All Tickets
                        </x-nav-link>
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                            Users
                        </x-nav-link>
                        <x-nav-link :href="route('faqs.index')" :active="request()->routeIs('faqs.*')">
                            Knowledge Base
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets.index') || request()->routeIs('tickets.show')">
                            My Tickets
                        </x-nav-link>
                        <x-nav-link :href="route('faqs.index')" :active="request()->routeIs('faqs.*')">
                            Knowledge Base
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown (Pushed completely to the right edge) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">

                <div class="relative">
                    <x-dropdown align="right" width="72">
                        <x-slot name="trigger">
                            <button class="relative inline-flex items-center p-2 text-gray-500 hover:text-gray-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @if (auth()->user()->unreadNotifications->count() > 0)
                                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white bg-red-600 rounded-full">
                                        {{ auth()->user()->unreadNotifications->count() }}
                                    </span>
                                @endif
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="max-h-80 overflow-y-auto">
                                @forelse (auth()->user()->notifications->take(10) as $notification)
                                    <a href="{{ route('tickets.show', $notification->data['ticket_id']) }}"
                                        class="block px-4 py-3 text-sm hover:bg-gray-100 {{ $notification->read_at ? 'text-gray-500' : 'text-gray-800 font-semibold bg-amber-50' }}">
                                        {{ $notification->data['message'] }}
                                        <div class="text-xs text-gray-400 mt-1 font-normal">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </div>
                                    </a>
                                @empty
                                    <div class="px-4 py-3 text-sm text-gray-500">No notifications yet.</div>
                                @endforelse
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>