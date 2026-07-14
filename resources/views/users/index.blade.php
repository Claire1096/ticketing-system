<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                User Management
            </h2>
            <a href="{{ route('users.create') }}"
                class="bg-gray-900 text-white font-bold px-5 py-2.5 rounded-md hover:bg-gray-700 text-sm">
                + Add User
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white border border-gray-200 rounded-lg divide-y divide-gray-100">
                @foreach ($users as $user)
                    <div class="flex items-center justify-between px-6 py-4">
                        <div>
                            <div class="font-bold text-gray-800">{{ $user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                        </div>
                        <div class="flex items-center gap-4">
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $user->role === 'technician' ? 'bg-pink-100 text-pink-700' : 'bg-gray-100 text-gray-600' }}">
                                         {{ ucfirst($user->role) }}
                                 </span>
                                 <a href="{{ route('users.edit', $user) }}" class="text-sm text-pink-600 font-semibold hover:underline">
                                              Edit
                                 </a>
                                             @if ($user->id !== auth()->id())
                                <form method="POST" action="{{ route('users.destroy', $user) }}"
                                    onsubmit="return confirm('Delete this user account?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 font-semibold hover:underline">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>