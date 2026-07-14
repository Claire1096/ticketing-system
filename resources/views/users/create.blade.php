<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add New User
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white border border-gray-200 rounded-lg p-8">

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('users.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Full name</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                        <input type="password" name="password"
                            class="w-full border-gray-300 rounded-md shadow-sm" required minlength="8">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Role</label>
                        <select name="role" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="employee">Employee</option>
                            <option value="technician">Technician</option>
                        </select>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit"
                            class="bg-gray-900 text-white font-bold px-6 py-3 rounded-md hover:bg-gray-700">
                            Create user
                        </button>
                        <a href="{{ route('users.index') }}"
                            class="border-2 border-gray-300 text-gray-600 font-bold px-6 py-3 rounded-md hover:border-gray-400">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>