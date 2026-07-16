<x-guest-layout>
    <div class="text-center mb-6">
        <div class="text-lg font-bold text-gray-800">IT Support Account Recovery</div>
        <p class="text-sm text-gray-500 mt-2">
            This is only for IT Support / Admin accounts. Employees who forgot their password should contact IT Support directly.
        </p>
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm font-medium text-green-600 text-center">
            {{ session('status') }}
        </div>
    @endif

    <x-input-error :messages="$errors->get('email')" class="mb-4" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
            placeholder="Your IT Support email"
            class="w-full border-gray-300 rounded-lg focus:border-pink-500 focus:ring-pink-500 text-sm">

        <button type="submit"
            class="w-full bg-pink-700 hover:bg-pink-800 text-white font-bold py-3 rounded-full transition">
            Send reset link
        </button>
    </form>

    <div class="text-center mt-5">
        <a href="{{ route('login') }}" class="text-xs text-gray-400 hover:underline">← Back to login</a>
    </div>
</x-guest-layout>