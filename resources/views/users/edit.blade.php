<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit User
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

                <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-5" id="editUserForm">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Full name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            New password <span class="font-normal text-gray-400">(leave blank to keep current)</span>
                        </label>
                        <input type="password" name="password"
                            class="w-full border-gray-300 rounded-md shadow-sm" minlength="8">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Role</label>
                        <select name="role" id="roleSelect" data-original="{{ $user->role }}"
                            class="w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="employee" {{ old('role', $user->role) === 'employee' ? 'selected' : '' }}>Employee</option>
                            <option value="technician" {{ old('role', $user->role) === 'technician' ? 'selected' : '' }}>IT Support</option>
                        </select>
                    </div>

                    {{-- Shown only when changing an existing IT Support account away from that role --}}
                    <div id="roleChangeWarning" class="hidden bg-yellow-50 border border-yellow-200 rounded-md p-4">
                        <p class="text-sm text-yellow-800 mb-3">
                            <strong>Heads up:</strong> this will remove Technician (IT Support) access from
                            {{ $user->name }}. If this is your only remaining IT Support account, the change will
                            be blocked — make sure another user has the Technician role first.
                        </p>
                        <label class="flex items-start gap-2 text-sm text-yellow-900">
                            <input type="checkbox" name="confirm_role_change" value="1"
                                {{ old('confirm_role_change') ? 'checked' : '' }} class="mt-1">
                            <span>I'm sure I want to change this account's role.</span>
                        </label>
                    </div>

    <div>
    <label class="block text-sm font-bold text-gray-700 mb-2">Department</label>
    <select name="department" class="w-full border-gray-300 rounded-md shadow-sm" required>
        <option value="">Select department</option>
        @foreach (['Sales and Marketing', 'Logistics', 'Human Resource', 'Finance and Accounting', 'IT Dept', 'Production', 'Executives'] as $dept)
            <option value="{{ $dept }}" {{ $user->department === $dept ? 'selected' : '' }}>{{ $dept }}</option>
        @endforeach
    </select>
</div>
                    <div class="flex gap-3 pt-2">
                        <button type="submit"
                            class="bg-gray-900 text-white font-bold px-6 py-3 rounded-md hover:bg-gray-700">
                            Save changes
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

    <script>
        (function () {
            const roleSelect = document.getElementById('roleSelect');
            const warning = document.getElementById('roleChangeWarning');
            const confirmCheckbox = warning.querySelector('input[name="confirm_role_change"]');
            const wasTechnician = roleSelect.dataset.original === 'technician';

            function toggleWarning() {
                const leavingTechnician = wasTechnician && roleSelect.value !== 'technician';
                warning.classList.toggle('hidden', !leavingTechnician);
                if (!leavingTechnician) {
                    confirmCheckbox.checked = false;
                }
            }

            roleSelect.addEventListener('change', toggleWarning);
            toggleWarning(); // handle page load after a validation error (old('role') may already differ)

            document.getElementById('editUserForm').addEventListener('submit', function (e) {
                const leavingTechnician = wasTechnician && roleSelect.value !== 'technician';
                if (leavingTechnician && !confirmCheckbox.checked) {
                    e.preventDefault();
                    confirmCheckbox.focus();
                }
            });
        })();
    </script>
</x-app-layout>