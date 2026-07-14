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


        CrmStorage::ensureSeeded();
        $updated = CrmStorage::updateFollowUpStatus($followUpId, $validated['status']);

        if ($updated === null) {
            return redirect()
                ->route('crm.followup')
                ->with('success', 'Follow-up not found.');
        }

        return redirect()
            ->back()
            ->with('success', 'Follow-up status updated.');
    }
}

