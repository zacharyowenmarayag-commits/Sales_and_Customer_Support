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

        $followUp = CrmStorage::updateFollowUpStatus($followUpId, $validated['status']);

        if (!$followUp) {
            return redirect()
                ->route('crm.followup')
                ->with('success', 'Follow-up not found.');
        }

        return redirect()
            ->back()
            ->with('success', 'Follow-up status updated.');
    }
}

