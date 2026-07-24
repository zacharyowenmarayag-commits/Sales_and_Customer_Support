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
            'task' => ['nullable', 'string', 'max:255'],
            'customer' => ['nullable', 'string', 'max:255'],
            'due_date' => ['nullable', 'string'],
            'priority' => ['nullable', 'string'],
            'status' => ['required', Rule::in(FollowUp::STATUSES)],
            'description' => ['nullable', 'string'],
        ]);

        $followUp = CrmStorage::updateFollowUp($followUpId, $validated);

        if (!$followUp) {
            return redirect()
                ->route('crm.followup')
                ->with('error', 'Follow-up not found.');
        }

        return redirect()
            ->back()
            ->with('success', 'Follow-up updated.');
    }
}

