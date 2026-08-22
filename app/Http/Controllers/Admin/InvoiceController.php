<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Appointment;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('appointment')
            ->orderBy('InvoiceID', 'desc')
            ->paginate(10);

        return view('admin.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $appointments = Appointment::orderBy('AppointmentDate', 'desc')->get();

        return view('admin.invoices.create', compact('appointments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'AppointmentID' => 'required|exists:Appointment,AppointmentID',
            'TotalAmount' => 'required|numeric|min:0',
            'DiscountAmount' => 'required|numeric|min:0',
            'FinalAmount' => 'required|numeric|min:0',
            'PaymentMethod' => 'nullable|string|max:30',
        ]);

        Invoice::create($validated);

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Thêm hóa đơn thành công!');
    }

    public function edit(Invoice $invoice)
    {
        $appointments = Appointment::orderBy('AppointmentDate', 'desc')->get();

        return view('admin.invoices.edit', compact(
            'invoice',
            'appointments'
        ));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'AppointmentID' => 'required|exists:Appointment,AppointmentID',
            'InvoiceDate' => 'required|date',
            'TotalAmount' => 'required|numeric|min:0',
            'DiscountAmount' => 'required|numeric|min:0',
            'FinalAmount' => 'required|numeric|min:0',
            'PaymentMethod' => 'nullable|string|max:30',
        ]);

        $invoice->update($validated);

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Cập nhật hóa đơn thành công!');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Xóa hóa đơn thành công!');
    }
}