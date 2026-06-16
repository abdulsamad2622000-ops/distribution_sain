<?php

namespace App\Http\Controllers;

use App\Models\Followup;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowupController extends Controller
{
    public function index()
    {
        $followups = Followup::with('lead', 'user')
                    ->where('status', 'pending')
                    ->orderBy('followup_date', 'asc')
                    ->paginate(20);
        return view('crm.followups.index', compact('followups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lead_id'       => 'required|exists:leads,id',
            'type'          => 'required|in:call,email,meeting,whatsapp,other',
            'followup_date' => 'required|date',
            'notes'         => 'nullable|string',
        ]);

        Followup::create([
            'lead_id'       => $request->lead_id,
            'user_id'       => Auth::id(),
            'type'          => $request->type,
            'followup_date' => $request->followup_date,
            'notes'         => $request->notes,
            'status'        => 'pending',
        ]);

        return back()->with('success', 'Follow-up scheduled.');
    }

    public function markDone(Followup $followup)
    {
        $followup->update(['status' => 'done']);
        return back()->with('success', 'Follow-up marked as done.');
    }

    public function destroy(Followup $followup)
    {
        $followup->delete();
        return back()->with('success', 'Follow-up deleted.');
    }
}