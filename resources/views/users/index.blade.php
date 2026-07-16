<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-lg text-pink-700">
                    User Management
            </h2>
            <a href="{{ route('users.create') }}"
    class="bg-pink-700 hover:bg-pink-800 text-white font-bold px-5 py-2.5 rounded-md text-sm transition">
    + Add User
            </a>
        </div>
    </x-slot>
<!-- Department Filter Bar -->
<!-- Department Filter Bar (Moved to the Far Right) -->
<div class="bg-white p-4 rounded-lg shadow mb-6 flex justify-end items-center">
    <form action="{{ route('users.index') }}" method="GET" class="flex items-center gap-3">
        <label for="department" class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Department:</label>
        
        <select name="department" id="department" onchange="this.form.submit()" class="text-sm rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
            <option value="">All Departments</option>
            <option value="Sales and Marketing" {{ request('department') == 'Sales and Marketing' ? 'selected' : '' }}>Sales and Marketing</option>
            <option value="Logistics" {{ request('department') == 'Logistics' ? 'selected' : '' }}>Logistics</option>
            <option value="Human Resource" {{ request('department') == 'Human Resource' ? 'selected' : '' }}>Human Resource</option>
            <option value="Finance and Accounting" {{ request('department') == 'Finance and Accounting' ? 'selected' : '' }}>Finance and Accounting</option>
            <option value="IT Dept" {{ request('department') == 'IT Dept' ? 'selected' : '' }}>IT Dept</option>
            <option value="Production" {{ request('department') == 'Production' ? 'selected' : '' }}>Production</option>
            <option value="Executives" {{ request('department') == 'Executives' ? 'selected' : '' }}>Executives</option>
        </select>

        @if(request()->filled('department'))
            <a href="{{ route('users.index') }}" class="text-xs text-pink-600 hover:text-pink-700 underline font-medium ml-1">
                Clear Filter
            </a>
        @endif
    </form>

    <!-- Right: Preserve your Add User button here if it's in the same container -->
</div>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-[#F5E9F7] border border-[#F3D9F0] text-[#8A1E73] px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white border border-[#F3D9F0] rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-[#F3D9F0] font-bold text-gray-800 bg-[#FBF0FA]">
                    All Users
                </div>

                <div class="divide-y divide-[#F3D9F0]">
                    @foreach ($users as $user)
                        <div class="flex items-center justify-between px-6 py-4 hover:bg-[#FBF0FA] transition">
                            <div>
                                <div class="font-bold text-gray-800">{{ $user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full"
                                    style="background: {{ $user->role === 'technician' ? '#F5E9F7' : '#F3F4F6' }}; color: {{ $user->role === 'technician' ? '#C026A3' : '#4B5563' }};">
                                    {{ $user->role === 'technician' ? 'IT Support' : 'Employee' }}
                                </span>
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full" style="background:#EEF2FF; color:#4F46E5;">
                                    {{ $user->department ?? '—' }}
                                </span>
                                <a href="{{ route('users.edit', $user) }}" class="text-sm font-semibold hover:underline" style="color:#C026A3;">
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
    </div>
</x-app-layout>