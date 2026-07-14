<?php

namespace App\Http\Controllers;

use App\Support\CrmStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CommunicationLogController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer' => ['required', 'string', 'max:255'],
            'channel' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'subject' => ['required', Rule::in(\App\Models\CommunicationLog::SUBJECTS)],
            'message' => ['nullable', 'string'],
        ]);

        $log = \App\Models\CommunicationLog::create([
            'customer' => $validated['customer'],
            'channel' => $validated['channel'],
            'email' => $validated['email'] ?? '',
            'phone' => $validated['phone'] ?? '',
            'subject' => $validated['subject'],
            'message' => $validated['message'] ?? '',
            'handled_by' => 'Admin',
        ]);

        if ($validated['subject'] === 'Order Follow-up') {
            $task = $validated['message']
                ? 'Order Follow-up: ' . Str::limit($validated['message'], 100)
                : 'Order Follow-up';

            \App\Models\FollowUp::create([
                'communication_log_id' => $log->id,
                'task' => $task,
                'customer' => $validated['customer'],
                'due_date' => now()->addDays(7),
                'status' => 'Pending',
            ]);
        }

        return redirect()
            ->route('crm.comlog')
            ->with('success', 'Communication log added successfully.');
    }
}
