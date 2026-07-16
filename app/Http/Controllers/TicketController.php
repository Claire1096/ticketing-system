<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Notifications\TicketViewedNotification;


class TicketController extends Controller
{
   // Show the logged-in user's tickets (and let technicians see and filter all tickets)
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // 1. If technician, query all tickets. Otherwise, query only the logged-in user's tickets.
        if ($user->role === 'technician') {
            $query = Ticket::query()->with('user');
        } else {
            $query = Ticket::where('submitted_by', $user->id);
        }

        // 2. Apply Month Filter
        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }

        // 3. Apply Year Filter
        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        // 4. Fetch the filtered results with pagination
        $tickets = $query->latest()->paginate(15)->withQueryString();

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
            'department' => 'required|in:Sales and Marketing,Logistics,Human Resource,Finance and Accounting,IT Dept,Production,Executives',
            'priority' => 'required|in:Low,Medium,High,Critical',
        ]);

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

        // --- NEW: Trigger viewed notification when a Technician opens the ticket for the first time ---
        if ($user->role === 'technician' && !$ticket->is_viewed_by_tech) {
            $ticket->update([
                'is_viewed_by_tech' => true,
                'tech_viewed_at' => now(),
            ]);

            // Notify the user who submitted the ticket
            $ticket->submittedBy->notify(new TicketViewedNotification($ticket, $user->name));
        }
        // --------------------------------------------------------------------------------------------

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
public function all(Request $request)
{

    dd("I am inside the ALL method!");
    $query = Ticket::query()->with('user');

    // 1. Filter by Month if the user selected one
    if ($request->filled('month')) {
        $query->whereMonth('created_at', $request->month);
    }

    // 2. Filter by Year if the user selected one
    if ($request->filled('year')) {
        $query->whereYear('created_at', $request->year);
    }

    // 3. Get the paginated results with the query parameters attached
    $tickets = $query->latest()->paginate(15)->withQueryString();

    return view('tickets.all', compact('tickets'));
}
    
}