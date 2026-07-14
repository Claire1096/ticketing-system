<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\FaqController;
use Illuminate\Support\Facades\Route;

    Route::get('/', function () {
    return view('welcome');
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
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';