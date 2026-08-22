<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('CustomerID', 'desc')
            ->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'FullName' => 'required|string|max:255',
            'Phone' => 'nullable|string|max:20',
            'Email' => 'nullable|email|max:255',
            'DOB' => 'nullable|date',
            'Allergies' => 'nullable|string|max:1000',
            'Notes' => 'nullable|string|max:2000',
            'MembershipTier' => 'nullable|string|max:50',
        ]);

        Customer::create([
            'FullName' => $validated['FullName'],
            'Phone' => $validated['Phone'] ?? null,
            'Email' => $validated['Email'] ?? null,
            'DOB' => $validated['DOB'] ?? null,
            'Allergies' => $validated['Allergies'] ?? null,
            'Notes' => $validated['Notes'] ?? null,
            'LoyaltyPoints' => 0,
            'MembershipTier' => $validated['MembershipTier'] ?? 'Bronze',
        ]);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function show($id)
    {
        $customer = Customer::findOrFail($id);

        return view('admin.customers.show', compact('customer'));
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);

        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'FullName' => 'required|string|max:255',
            'Phone' => 'nullable|string|max:20',
            'Email' => 'nullable|email|max:255',
            'DOB' => 'nullable|date',
            'Allergies' => 'nullable|string|max:1000',
            'Notes' => 'nullable|string|max:2000',
            'MembershipTier' => 'nullable|string|max:50',
        ]);

        $customer->update([
            'FullName' => $validated['FullName'],
            'Phone' => $validated['Phone'] ?? null,
            'Email' => $validated['Email'] ?? null,
            'DOB' => $validated['DOB'] ?? null,
            'Allergies' => $validated['Allergies'] ?? null,
            'Notes' => $validated['Notes'] ?? null,
            'MembershipTier' => $validated['MembershipTier'] ?? 'Bronze',
        ]);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);

        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}