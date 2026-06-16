<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $status   = $request->status ?? '';
        $priority = $request->priority ?? '';

        $leads = Lead::with('assignedUser')
            ->when($status,   fn($q) => $q->where('status', $status))
            ->when($priority, fn($q) => $q->where('priority', $priority))
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'new'         => Lead::where('status', 'new')->count(),
            'contacted'   => Lead::where('status', 'contacted')->count(),
            'negotiation' => Lead::where('status', 'negotiation')->count(),
            'won'         => Lead::where('status', 'won')->count(),
            'lost'        => Lead::where('status', 'lost')->count(),
        ];

        return view('crm.leads.index', compact('leads', 'stats', 'status', 'priority'));
    }

    public function create()
    {
        $users = User::all();
        return view('crm.leads.form', ['lead' => new Lead(), 'users' => $users]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'nullable|string|max:20',
            'email'    => 'nullable|email|max:255',
            'status'   => 'required|in:new,contacted,negotiation,won,lost',
            'priority' => 'required|in:low,medium,high',
        ]);

        Lead::create($request->all());

        return redirect()->route('leads.index')
                         ->with('success', 'Lead created successfully.');
    }

    public function show(Lead $lead)
    {
        $lead->load('assignedUser', 'followups.user', 'interactions.user');
        return view('crm.leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        $users = User::all();
        return view('crm.leads.form', compact('lead', 'users'));
    }

    public function update(Request $request, Lead $lead)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'status'   => 'required|in:new,contacted,negotiation,won,lost',
            'priority' => 'required|in:low,medium,high',
        ]);

        $lead->update($request->all());

        return redirect()->route('leads.show', $lead)
                         ->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('leads.index')
                         ->with('success', 'Lead deleted.');
    }

    public function pipeline()
    {
        $stages = ['new', 'contacted', 'negotiation', 'won', 'lost'];
        $pipeline = [];
        foreach ($stages as $stage) {
            $pipeline[$stage] = Lead::where('status', $stage)
                                ->orderBy('created_at', 'desc')->get();
        }
        return view('crm.leads.pipeline', compact('pipeline'));
    }
}