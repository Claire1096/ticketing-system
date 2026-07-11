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
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:Hardware,Software,Network,Printer,Internet,Others',
            'priority' => 'required|in:Low,Medium,High,Critical',
        ]);

        $validated['submitted_by'] = auth()->id();
        $validated['status'] = 'Open';

        Ticket::create($validated);

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket submitted successfully.');
    }

    // Show a single ticket's details
    public function show(Ticket $ticket)
    {
        return view('tickets.show', compact('ticket'));
    }

    // Update a ticket (status change, technician remarks, assignment)
    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:Open,In Progress,Pending,Resolved,Closed',
            'technician_remarks' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if ($validated['status'] === 'Resolved' && $ticket->status !== 'Resolved') {
            $validated['resolved_at'] = now();
        }

        $ticket->update($validated);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket updated.');
    }
}
