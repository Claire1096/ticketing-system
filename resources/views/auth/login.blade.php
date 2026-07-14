<x-guest-layout>

    {{-- Logo + company name --}}
  <div class="flex items-center justify-center gap-3 mb-6">
    <img src="{{ asset('logo.svg') }}" alt="EM Power Beautiful Skin" class="w-14 h-14">
    <div class="text-left">
        <div class="text-lg font-bold text-gray-800 leading-tight">
            <span class="text-pink-600">EM</span> Power Beautiful Skin
        </div>
        <div class="text-[10px] tracking-[0.2em] text-gray-500 font-semibold">CORPORATION</div>
    </div>
</div>

    <p class="text-center text-sm text-gray-500 mb-6">
        Use your EM Power employee account to submit and track IT tickets.
    </p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email / Username -->
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </span>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                placeholder="Username"
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg bg-white focus:border-pink-500 focus:ring-pink-500 text-sm">
        </div>
        <x-input-error :messages="$errors->get('email')" class="mt-1" />

        <!-- Password -->
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </span>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                placeholder="Password"
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg bg-white focus:border-pink-500 focus:ring-pink-500 text-sm">
        </div>
        <x-input-error :messages="$errors->get('password')" class="mt-1" />

        <!-- Remember Me -->
        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 text-gray-500">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-pink-600 focus:ring-pink-500">
                Remember me
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-pink-600 font-semibold hover:underline">
                    Forgot password?
                </a>
            @endif
        </div>

        <button type="submit"
            class="w-full bg-pink-300 hover:bg-pink-400 text-gray-800 font-bold py-3 rounded-full transition">
            Login
        </button>

    </form>
</x-guest-layout>