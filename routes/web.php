<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

    Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'technician'
            ? redirect()->route('technician.dashboard')
            : redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

    Route::get('/dashboard', function () {
    $user = auth()->user();

    $myTickets = \App\Models\Ticket::where('submitted_by', $user->id)->latest()->get();

    $openCount = $myTickets->whereIn('status', ['Open', 'In Progress', 'Pending'])->count();
    $resolvedCount = $myTickets->where('status', 'Resolved')->count();
    $recentTickets = $myTickets->take(3);

    return view('dashboard', compact('openCount', 'resolvedCount', 'recentTickets'));
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
    // Employee-facing routes
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');
    Route::get('/knowledge-base', [FaqController::class, 'index'])->name('faqs.index');

    // Technician-only routes
    Route::middleware('technician')->group(function () {
    Route::get('/technician/dashboard', [TechnicianController::class, 'dashboard'])->name('technician.dashboard');
    Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
    Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');

    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';