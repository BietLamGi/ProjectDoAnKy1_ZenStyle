<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Customer list - supports search by name/phone number.
     */
    public function index(Request $request)
    {
        $keyword = $request->query('q');

        // AppointmentID is an int column on SQL Server: comparing it to a
        // non-numeric string throws a conversion error rather than just
        // finding no rows, so only add that branch when the keyword (minus
        // an optional leading '#') is actually numeric.
        $appointmentCode = ltrim((string) $keyword, '#');
        $isAppointmentCode = $appointmentCode !== '' && ctype_digit($appointmentCode);

        $customers = Customer::query()
    ->withCount('appointments')
    ->when($keyword, function ($query) use ($keyword, $appointmentCode, $isAppointmentCode) {
                $query->where('FullName', 'like', "%{$keyword}%")
                    ->orWhere('Phone', 'like', "%{$keyword}%")
                    ->orWhere('Email', 'like', "%{$keyword}%")
                    // Search by appointment code too, e.g. typing "12" or "#12"
                    // matches the customer who owns AppointmentID 12.
                    ->when($isAppointmentCode, function ($query) use ($appointmentCode) {
                        $query->orWhereHas('appointments', function ($q) use ($appointmentCode) {
                            $q->where('AppointmentID', $appointmentCode);
                        });
                    });
            })
            ->orderByDesc('CustomerID')
            ->paginate(10)
            ->withQueryString();

        return view('receptionist.customers.index', compact('customers', 'keyword'));
    }

    /**
     * Form to add a new customer (walk-in reception).
     */
    public function create()
    {
        return view('receptionist.customers.create');
    }

    /**
     * Save a new customer.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'FullName' => 'required|max:100',
            'Phone' => 'required|max:20|unique:Customer,Phone',
            'Email' => 'nullable|email|max:100',
            'DOB' => 'nullable|date',
            'Allergies' => 'nullable|max:255',
            'Notes' => 'nullable|max:500',
            'MembershipTier' => 'nullable|max:30',
        ]);

        $data['LoyaltyPoints'] = 0;
        $data['MembershipTier'] = $data['MembershipTier'] ?? 'Normal';

        $customer = Customer::create($data);

        return redirect()
            ->route('receptionist.customers.index')
            ->with('success', 'Customer "' . $customer->FullName . '" added.');
    }

    /**
     * View customer profile: appointment history & invoices.
     */
    public function show(Customer $customer)
    {
        $customer->load([
            'appointments' => function ($query) {
                $query->orderByDesc('AppointmentDate')->orderByDesc('StartTime');
            },
            'appointments.services.service',
            'invoices' => function ($query) {
                $query->orderByDesc('InvoiceID');
            },
        ]);

        return view('receptionist.customers.show', compact('customer'));
    }

    /**
     * Form to edit a customer.
     */
    public function edit(Customer $customer)
    {
        return view('receptionist.customers.edit', compact('customer'));
    }

    /**
     * Update customer information.
     */
    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'FullName' => 'required|max:100',
            'Phone' => 'required|max:20|unique:Customer,Phone,' . $customer->CustomerID . ',CustomerID',
            'Email' => 'nullable|email|max:100',
            'DOB' => 'nullable|date',
            'Allergies' => 'nullable|max:255',
            'Notes' => 'nullable|max:500',
            'MembershipTier' => 'nullable|max:30',
        ]);

        $customer->update($data);

        return redirect()
            ->route('receptionist.customers.index')
            ->with('success', 'Customer information updated.');
    }

    /**
     * Delete a customer (only when there is no important related data left).
     */
   public function destroy(Customer $customer)
    {
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($customer) {
                $appointmentIds = $customer->appointments()->pluck('AppointmentID');

                \App\Models\AppointmentService::whereIn('AppointmentID', $appointmentIds)->delete();

                \App\Models\Invoice::whereIn('AppointmentID', $appointmentIds)
                    ->orWhere('CustomerID', $customer->CustomerID)
                    ->delete();

                $customer->appointments()->delete();

                $customer->delete();
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()
                ->route('receptionist.customers.index')
                ->with('error', 'Cannot delete customer "' . $customer->FullName . '" because related data still exists in the system.');
        }

        return redirect()
            ->route('receptionist.customers.index')
            ->with('success', 'Customer deleted along with all related appointments and invoices.');
    }
}
