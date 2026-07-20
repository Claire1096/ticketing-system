<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
   public function index(Request $request) 
    {
        $query = User::query();

        // Filter by department if selected
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('users.index', compact('users'));
    }
    
    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users,email',
    'password' => 'required|string|min:8',
    'role' => 'required|in:employee,technician',
    'department' => 'required|in:Sales and Marketing,Logistics,Human Resource,Finance and Accounting,IT Dept,Production,Executives',
]);
        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }
public function edit(User $user)
{
    return view('users.edit', compact('user'));
}

public function update(Request $request, User $user)
{
    $isDemotingLastTechnician = $user->role === 'technician'
        && $request->input('role') !== 'technician';

    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'role' => 'required|in:employee,technician',
        'department' => 'required|in:Sales and Marketing,Logistics,Human Resource,Finance and Accounting,IT Dept,Production,Executives',
        'password' => 'nullable|string|min:8',
    ];

    // Only require the explicit confirmation checkbox when this change would
    // actually strip the technician (IT support) role from this user.
    if ($isDemotingLastTechnician) {
        $rules['confirm_role_change'] = 'required|accepted';
    }

    $validated = $request->validate($rules);

    // Guard: don't allow the last technician account to be changed to a
    // non-technician role — that would lock everyone out of the IT pages.
    if ($isDemotingLastTechnician) {
        $otherTechnicianCount = User::where('role', 'technician')
            ->where('id', '!=', $user->id)
            ->count();

        if ($otherTechnicianCount === 0) {
            return back()
                ->withInput()
                ->withErrors([
                    'role' => 'You cannot change this account\'s role to Employee — it is currently the only Technician (IT support) account. Assign the Technician role to another user first.',
                ]);
        }
    }

    unset($validated['confirm_role_change']);

    if (!empty($validated['password'])) {
        $validated['password'] = bcrypt($validated['password']);
    } else {
        unset($validated['password']);
    }

    $user->update($validated);

    return redirect()->route('users.index')
        ->with('success', 'User updated successfully.');
}



    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            abort(403, "You can't delete your own account.");
        }

        // Guard: don't allow deletion of the last remaining technician
        // (IT support) account — same risk as demoting them.
        if ($user->role === 'technician') {
            $otherTechnicianCount = User::where('role', 'technician')
                ->where('id', '!=', $user->id)
                ->count();

            if ($otherTechnicianCount === 0) {
                return redirect()->route('users.index')
                    ->with('error', 'You cannot delete this account — it is currently the only Technician (IT support) account. Assign the Technician role to another user first.');
            }
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted.');
    }

    
}