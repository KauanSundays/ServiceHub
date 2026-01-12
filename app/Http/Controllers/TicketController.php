<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessTicketAttachment;
use App\Models\Company;
use App\Models\Ticket;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = Ticket::with(['project.company', 'user'])
            ->when($request->search, fn($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->when($request->statuses, fn($q, $s) => $q->whereIn('status', $s))
            ->when($request->companies, fn($q, $c) => $q->whereHas('project', fn($qp) => $qp->whereIn('company_id', $c)))
            ->when($request->projects, fn($q, $p) => $q->whereIn('project_id', $p))
            ->latest()
            ->paginate(10)->withQueryString();

        return Inertia::render('Tickets/Index', [
            'tickets' => $tickets,
            'filters' => $request->only(['search', 'statuses', 'companies', 'projects']),
            'companies' => Company::all(['id', 'name']),
            'projects' => Project::all(['id', 'name']),
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
            'attachment' => 'nullable|file|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments');
        }

        $ticket = Ticket::create([
            'title' => $validated['title'],
            'project_id' => $validated['project_id'],
            'user_id' => auth()->id(),
            'status' => 'pending',
            'attachment_path' => $path,
        ]);

        ProcessTicketAttachment::dispatch($ticket);

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
