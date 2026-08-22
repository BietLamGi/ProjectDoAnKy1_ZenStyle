<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Appointment;

class TrackController extends Controller
{
    public function index()
    {
        return view('customer.track-order.index');
    }

    public function search(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
        ]);

        $phone = trim($request->phone);

        // Tìm Customer bằng Phone
        $customer = Customer::where('Phone', $phone)->first();

        // Không tìm thấy Customer
        if (!$customer) {
    return redirect()
        ->route('track-order.index')
        ->withInput()
        ->with('error', 'No customer found with this phone number.');
}

        
        // ORDERS
        $invoices = Invoice::where(
            'CustomerID',
            $customer->CustomerID
        )
        ->with([
            'details.service'
        ])
        ->orderBy('InvoiceDate', 'desc')
        ->get();

        // APPOINTMENTS
        $appointments = Appointment::where(
            'CustomerID',
            $customer->CustomerID
        )
        ->with([
            'services',
            'customer'
        ])
        ->orderBy('AppointmentDate', 'desc')
        ->get();


        return view(
            'customer.track-order.index',
            compact(
                'customer',
                'phone',
                'invoices',
                'appointments'
            )
        ) ->with(
            'searched',
            true
        );
    }
}