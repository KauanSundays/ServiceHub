<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Project;
use App\Jobs\ProcessTicketAttachment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        return Inertia::render('Tickets/Index', [
            'tickets' => Ticket::with(['project.company', 'detail', 'user'])->latest()->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Tickets/Create', [
            'projects' => Project::with('company')->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'attachment' => 'nullable|file|mimes:json,txt|max:2048',
        ]);

        $path = $request->hasFile('attachment')
            ? $request->file('attachment')->store('attachments')
            : null;

        $ticket = Ticket::create([
            'project_id' => $request->project_id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'attachment_path' => $path,
            'status' => 'pending',
        ]);

        if ($path) {
            ProcessTicketAttachment::dispatch($ticket);
        }

        return redirect()->route('tickets.index');
    }

    public function show(Ticket $ticket)
    {
        return Inertia::render('Tickets/Show', [
            'ticket' => $ticket->load(['project.company', 'detail', 'user'])
        ]);
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return redirect()->route('tickets.index');
    }
}
