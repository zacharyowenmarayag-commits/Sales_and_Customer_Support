<?php

namespace App\Http\Controllers;

use App\Support\CrmStorage;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
        ]);

        CrmStorage::createCustomer([
            'name' => trim($validated['first_name'] . ' ' . $validated['last_name']),
            'email' => $validated['email'] ? trim($validated['email']) : null,
            'phone' => $validated['phone'] ? trim($validated['phone']) : null,
        ]);

        return redirect()
            ->route('crm.customers')
            ->with('success', 'Customer added successfully.');
    }
}
