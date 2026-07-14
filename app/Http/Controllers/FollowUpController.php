<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
use App\Support\CrmStorage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FollowUpController extends Controller
{
    public function update(Request $request, int $followUpId)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(FollowUp::STATUSES)],
        ]);

        $followUp = \App\Models\FollowUp::find($followUpId);

        if (!$followUp) {
            return redirect()
                ->route('crm.followup')
                ->with('success', 'Follow-up not found.');
        }

        $followUp->update(['status' => $validated['status']]);

        return redirect()
            ->back()
            ->with('success', 'Follow-up status updated.');
    }
}

