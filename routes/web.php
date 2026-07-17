<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\FeedbackController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\Ticket;
use App\Models\User;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'technician'
            ? redirect()->route('technician.dashboard')
            : redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

Route::get('/api/tickets/monthly/stats/{year}/{month}', function ($year, $month) {
    $total = Ticket::whereYear('created_at', $year)
        ->whereMonth('created_at', $month)->count();

    $resolved = Ticket::where('status', 'Resolved')
        ->whereYear('resolved_at', $year)
        ->whereMonth('resolved_at', $month)->count();

    return response()->json(['total' => $total, 'resolved' => $resolved]);
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    $myTickets = Ticket::where('submitted_by', $user->id)->latest()->get();

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
    Route::get('/feedback', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

    // Technician-only routes
    Route::middleware('technician')->group(function () {
        Route::get('/technician/dashboard', [TechnicianController::class, 'dashboard'])->name('technician.dashboard');
        Route::get('/all-tickets', [TechnicianController::class, 'allTickets'])->name('tickets.all');
        Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
        Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
        Route::get('/knowledge-base/create', [FaqController::class, 'create'])->name('faqs.create');
        Route::post('/knowledge-base', [FaqController::class, 'store'])->name('faqs.store');
        Route::get('/technician/kpi-report', [TechnicianController::class, 'exportKpiReport'])->name('technician.kpi-report');
        Route::get('/feedback/all', [FeedbackController::class, 'index'])->name('feedback.index');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $user = User::where('email', $request->email)->first();

    if (!$user || $user->role !== 'technician') {
        return back()->withErrors([
            'email' => 'This isn\'t a registered IT Support account. Employees should contact IT Support directly to reset their password.',
        ]);
    }

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::ResetLinkSent
        ? back()->with('status', __($status))
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

require __DIR__ . '/auth.php';