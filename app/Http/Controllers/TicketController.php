<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;


class TicketController extends Controller
{
    // Show the logged-in user's tickets (the "My Tickets" page)
    public function index()
    {
        $tickets = Ticket::where('submitted_by', auth()->id())
            ->latest()
            ->get();

        return view('tickets.index', compact('tickets'));
    }

    // Show the "Submit a Ticket" form
    public function create()
    {
        return view('tickets.create');
    }

    // Handle the form submission
   public function store(Request $request)
{
    $validated = $request->validate([
        'description' => 'required|string',
        'category' => 'required|in:Hardware,Software,Network,Printer,Internet,Others',
        'priority' => 'required|in:Low,Medium,High,Critical',
    ]);

    $validated['subject'] = \Illuminate\Support\Str::limit($validated['description'], 60);
    $validated['submitted_by'] = auth()->id();
    $validated['status'] = 'Open';

    $ticket = Ticket::create($validated);

    $technicians = \App\Models\User::where('role', 'technician')->get();
    foreach ($technicians as $technician) {
        $technician->notify(new \App\Notifications\NewTicketNotification($ticket));
    }

    return redirect()->route('tickets.index')
        ->with('success', 'Ticket submitted successfully.');
}

    // Show a single ticket's details
   public function show(Ticket $ticket)
{
    $user = auth()->user();

    if ($user->role !== 'technician' && $ticket->submitted_by !== $user->id) {
        abort(403, 'You can only view tickets you submitted.');
    }

    $user->unreadNotifications
        ->where('data.ticket_id', $ticket->id)
        ->each->markAsRead();

    return view('tickets.show', compact('ticket'));
}
    public function edit(Ticket $ticket)
    {
        return view('tickets.edit', compact('ticket'));
    }

    // Update a ticket (status change, technician remarks, assignment)
  public function update(Request $request, Ticket $ticket)
{
    $validated = $request->validate([
        'status' => 'required|in:Open,In Progress,Pending,Resolved,Closed',
        'technician_remarks' => 'nullable|string',
        'assigned_to' => 'nullable|exists:users,id',
    ]);

    $justResolved = $validated['status'] === 'Resolved' && $ticket->status !== 'Resolved';

    if ($justResolved) {
        $validated['resolved_at'] = now();
    }

    // Mark the very first time a technician touches this ticket
    if (is_null($ticket->first_response_at)) {
        $validated['first_response_at'] = now();
    }

    $ticket->update($validated);

    if ($justResolved) {
        $ticket->submittedBy->notify(new \App\Notifications\TicketResolvedNotification($ticket));
    }

    return redirect()->route('tickets.show', $ticket)
        ->with('success', 'Ticket updated.');
}
    public function destroy(Ticket $ticket)
{
    $user = auth()->user();

    if ($user->role !== 'technician' && $ticket->submitted_by !== $user->id) {
        abort(403, 'You can only delete tickets you submitted.');
    }

    $ticket->delete();

    return redirect()->route('tickets.index')
        ->with('success', 'Ticket deleted.');
}
}
