<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        $search = $request->query('search');

        $faqs = Faq::query()
            ->when($category, fn ($query) => $query->where('category', $category))
            ->when($search, fn ($query) => $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                  ->orWhere('answer', 'like', "%{$search}%");
            }))
            ->latest()
            ->get();

        return view('faqs.index', compact('faqs', 'category', 'search'));
    }

    public function create()
    {
        return view('faqs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'required|in:Hardware,Software,Network,Printer,Internet,Others',
        ]);

        Faq::create($validated);

        return redirect()->route('faqs.index')
            ->with('success', 'FAQ added successfully.');
    }
}