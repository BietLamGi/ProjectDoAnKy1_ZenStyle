<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Danh sách hoá đơn.
     */
    public function index(Request $request)
    {
        $keyword = $request->query('q');

        $invoices = Invoice::with(['appointment.customer', 'customer'])
            ->when($keyword, function ($query) use ($keyword) {
                $query->whereHas('customer', function ($q) use ($keyword) {
                    $q->where('FullName', 'like', "%{$keyword}%")
                        ->orWhere('Phone', 'like', "%{$keyword}%");
                });
            })
            ->orderBy('InvoiceID', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('receptionist.invoices.index', compact('invoices', 'keyword'));
    }

    /**
     * Form lập hoá đơn mới. Có thể lập từ 1 lịch hẹn cụ thể (?appointment_id=..)
     * để tự động tính tổng tiền từ các dịch vụ đã đặt.
     */
    public function create(Request $request)
    {
        $appointments = Appointment::with('customer')
            ->orderBy('AppointmentDate', 'desc')
            ->get();

        $selectedAppointment = null;
        $totalAmount = 0;

        if ($request->filled('appointment_id')) {
            $selectedAppointment = Appointment::with(['customer', 'services.service'])
                ->find($request->appointment_id);

            if ($selectedAppointment) {
                // Nếu lịch hẹn này đã có hoá đơn thì chuyển sang sửa hoá đơn đó luôn.
                $existingInvoice = Invoice::where('AppointmentID', $selectedAppointment->AppointmentID)->first();

                if ($existingInvoice) {
                    return redirect()
                        ->route('receptionist.invoices.edit', $existingInvoice)
                        ->with('success', 'Lịch hẹn này đã có hoá đơn, bạn có thể chỉnh sửa tại đây.');
                }

                $totalAmount = $selectedAppointment->services->sum(function ($line) {
                    return $line->Quantity * $line->UnitPrice;
                });
            }
        }

        return view('receptionist.invoices.create', compact(
            'appointments',
            'selectedAppointment',
            'totalAmount'
        ));
    }

    /**
     * Lưu hoá đơn mới.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'AppointmentID' => 'required|exists:Appointment,AppointmentID',
            'TotalAmount' => 'required|numeric|min:0',
            'DiscountAmount' => 'required|numeric|min:0',
            'PaymentMethod' => 'nullable|string|max:30',
        ]);

        $alreadyExists = Invoice::where('AppointmentID', $validated['AppointmentID'])->exists();

        if ($alreadyExists) {
            return back()
                ->withInput()
                ->with('error', 'This appointment already has an invoice.');
        }

        $appointment = Appointment::find($validated['AppointmentID']);

        $validated['CustomerID'] = $appointment->CustomerID ?? null;
        $validated['InvoiceDate'] = now();
        $validated['TotalAmount'] = (float) $validated['TotalAmount'];
        $validated['DiscountAmount'] = (float) $validated['DiscountAmount'];
        $validated['FinalAmount'] = max(0, $validated['TotalAmount'] - $validated['DiscountAmount']);

        Invoice::create($validated);

        return redirect()
            ->route('receptionist.invoices.index')
            ->with('success', 'Đã lập hoá đơn thành công.');
    }

    /**
     * Xem chi tiết hoá đơn.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['appointment.customer', 'appointment.services.service', 'customer']);

        return view('receptionist.invoices.show', compact('invoice'));
    }

    /**
     * Form sửa hoá đơn.
     */
    public function edit(Invoice $invoice)
    {
        $invoice->load('appointment.services.service');

        $appointments = Appointment::with('customer')
            ->orderBy('AppointmentDate', 'desc')
            ->get();

        return view('receptionist.invoices.edit', compact(
            'invoice',
            'appointments'
        ));
    }

    /**
     * Cập nhật hoá đơn.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'AppointmentID' => 'required|exists:Appointment,AppointmentID',
            'InvoiceDate' => 'required|date',
            'TotalAmount' => 'required|numeric|min:0',
            'DiscountAmount' => 'required|numeric|min:0',
            'PaymentMethod' => 'nullable|string|max:30',
        ]);

        $appointment = Appointment::find($validated['AppointmentID']);
        $validated['CustomerID'] = $appointment->CustomerID ?? null;
        $validated['TotalAmount'] = (float) $validated['TotalAmount'];
        $validated['DiscountAmount'] = (float) $validated['DiscountAmount'];
        $validated['FinalAmount'] = max(0, $validated['TotalAmount'] - $validated['DiscountAmount']);

        $invoice->update($validated);

        return redirect()
            ->route('receptionist.invoices.index')
            ->with('success', 'Đã cập nhật hoá đơn.');
    }

    /**
     * Xoá hoá đơn.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()
            ->route('receptionist.invoices.index')
            ->with('success', 'Đã xoá hoá đơn.');
    }
}
