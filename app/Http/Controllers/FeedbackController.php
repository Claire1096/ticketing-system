<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Ticket;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function create()
    {
        return view('feedback.create');
    }
public function store(Request $request)
{  


    $validated = $request->validate([
        'message' => 'required|string|max:1000',
        'rating' => 'nullable|integer|min:1|max:5',
        'ticket_id' => 'nullable|exists:tickets,id',
    ]);

    
    $validated['submitted_by'] = auth()->id();

   $feedback = Feedback::create($validated);

    // Add this part: Notify the IT Support user
    // Assuming your IT user has the role 'technician' or a specific email
    $itSupport = \App\Models\User::where('email', 'itsupport@empowerbeautifulskin.com')->first();
    
    if ($itSupport) {
        // You will need to create this notification class (see step 2)
        $itSupport->notify(new \App\Notifications\NewFeedbackReceived($feedback));
    }

    return back()->with('success', 'Thanks for your feedback!');

    Feedback::create($validated);

    return back()->with('success', 'Thanks for your feedback!');

    
}

    public function index()
    {
        $feedbacks = Feedback::latest()->get();

        return view('feedback.index', compact('feedbacks'));
    }
}