<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function index()
    {
        return Inertia::render('Tickets/Index', [
            'tickets' => Ticket::with(['project.company', 'user'])->latest()->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Tickets/Create', [
            'projects' => Project::all(['id', 'name'])
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        Ticket::create($validated);

        return redirect()->route('tickets.index');
    }

    public function edit(Ticket $ticket)
    {
        return Inertia::render('Tickets/Edit', [
            'ticket' => $ticket,
            'projects' => Project::all(['id', 'name'])
        ]);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'status' => 'required|in:pending,processing,completed',
        ]);

        $ticket->update($validated);

        return redirect()->route('tickets.index');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index');
    }
}
