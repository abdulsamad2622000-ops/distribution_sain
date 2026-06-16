<?php

namespace App\Http\Controllers;

use App\Models\Interaction;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InteractionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'lead_id'          => 'required|exists:leads,id',
            'type'             => 'required|in:call,email,meeting,whatsapp,note,other',
            'subject'          => 'nullable|string|max:255',
            'description'      => 'required|string',
            'interaction_date' => 'required|date',
        ]);

        Interaction::create([
            'lead_id'          => $request->lead_id,
            'user_id'          => Auth::id(),
            'type'             => $request->type,
            'subject'          => $request->subject,
            'description'      => $request->description,
            'interaction_date' => $request->interaction_date,
        ]);

        return back()->with('success', 'Interaction logged.');
    }

    public function destroy(Interaction $interaction)
    {
        $interaction->delete();
        return back()->with('success', 'Interaction deleted.');
    }
}