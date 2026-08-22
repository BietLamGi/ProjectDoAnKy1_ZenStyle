<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Tính tổng hoá đơn từ danh sách dịch vụ đã book trên appointment, áp
     * dụng khuyến mãi ĐANG ACTIVE của TỪNG dịch vụ một cách tự động (không
     * cần lễ tân chọn thủ công). Một appointment có nhiều dịch vụ, mỗi dịch
     * vụ có khuyến mãi riêng (hoặc không có) thì mỗi dòng được tính giảm giá
     * độc lập - không giới hạn chỉ 1 khuyến mãi cho cả hoá đơn.
     *
     * Discount được tính trên UnitPrice đã lưu tại thời điểm đặt lịch (không
     * lấy lại Service->Price hiện tại, để tránh trôi giá nếu giá dịch vụ đổi
     * sau khi khách đã đặt).
     */
    private function calculateInvoiceTotals(Appointment $appointment): array
    {
        $appointment->loadMissing('services.service.activePromotion');

        $lines = [];
        $totalAmount = 0.0;
        $finalAmount = 0.0;

        foreach ($appointment->services as $line) {
            $quantity = (float) $line->Quantity;
            $unitPrice = (float) $line->UnitPrice;
            $lineOriginal = $quantity * $unitPrice;

            $promotion = optional($line->service)->activePromotion;
            $unitFinal = $unitPrice;

            if ($promotion) {
                if ($promotion->DiscountType === 'Percent') {
                    $unitFinal = $unitPrice - ($unitPrice * (float) $promotion->DiscountValue / 100);
                } else {
                    $unitFinal = $unitPrice - (float) $promotion->DiscountValue;
                }
                $unitFinal = max(0, $unitFinal);
            }

            $lineFinal = $unitFinal * $quantity;

            $lines[] = [
                'service_name' => optional($line->service)->ServiceName ?? '—',
                'quantity' => $line->Quantity,
                'unit_price' => $unitPrice,
                'promotion' => $promotion,
                'unit_final' => $unitFinal,
                'line_original' => $lineOriginal,
                'line_final' => $lineFinal,
            ];

            $totalAmount += $lineOriginal;
            $finalAmount += $lineFinal;
        }

        return [
            'lines' => $lines,
            'totalAmount' => $totalAmount,
            'discountAmount' => max(0, $totalAmount - $finalAmount),
            'finalAmount' => $finalAmount,
        ];
    }

    /**
     * Invoice list.
     *
     * "Needs follow-up" here covers two different things, since PaymentMethod
     * is nullable and there's no separate "unpaid" state in this system:
     *   1. An Invoice that exists but has no PaymentMethod recorded yet.
     *   2. A booked (non-cancelled) Appointment that has no Invoice at all
     *      yet - it wouldn't otherwise appear anywhere on this page since
     *      it isn't an Invoice row.
     * $unconfirmedCount and the "No payment method only" filter cover both.
     */
    public function index(Request $request)
    {
        $keyword = $request->query('q');
        $onlyUnconfirmed = $request->boolean('unconfirmed');

        $invoices = Invoice::with(['appointment.customer', 'customer'])
            ->when($keyword, function ($query) use ($keyword) {
                $query->whereHas('customer', function ($q) use ($keyword) {
                    $q->where('FullName', 'like', "%{$keyword}%")
                        ->orWhere('Phone', 'like', "%{$keyword}%");
                });
            })
            ->when($onlyUnconfirmed, function ($query) {
                $query->where(function ($q) {
                    $q->whereNull('PaymentMethod')->orWhere('PaymentMethod', '');
                });
            })
            ->orderBy('InvoiceID', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Appointments that haven't been billed yet - these have no Invoice
        // row, so they never show up above. Only surfaced when actively
        // looking at "No payment method only", alongside real invoices that
        // are missing a payment method, so the receptionist can see every
        // customer still needing action in one place.
        $appointmentsAwaitingInvoice = collect();

        if ($onlyUnconfirmed) {
            $appointmentsAwaitingInvoice = Appointment::with('customer')
                ->where('Status', '!=', 'Cancelled')
                ->doesntHave('invoice')
                ->when($keyword, function ($query) use ($keyword) {
                    $query->whereHas('customer', function ($q) use ($keyword) {
                        $q->where('FullName', 'like', "%{$keyword}%")
                            ->orWhere('Phone', 'like', "%{$keyword}%");
                    });
                })
                ->orderBy('AppointmentDate', 'desc')
                ->get();
        }

        $unconfirmedCount = Invoice::where(function ($q) {
                $q->whereNull('PaymentMethod')->orWhere('PaymentMethod', '');
            })->count()
            + Appointment::where('Status', '!=', 'Cancelled')->doesntHave('invoice')->count();

        return view('receptionist.invoices.index', compact(
            'invoices',
            'keyword',
            'onlyUnconfirmed',
            'unconfirmedCount',
            'appointmentsAwaitingInvoice'
        ));
    }

    /**
     * Record which payment method a customer used for an invoice that was
     * created without one. This does NOT touch Status/Completed/locking -
     * an Invoice already means "the visit is settled" in this system; this
     * only fills in the missing payment-method detail so it stops showing
     * up as needing follow-up.
     */
    public function confirmPayment(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'PaymentMethod' => 'required|string|max:30',
        ]);

        $invoice->update($validated);

        return back()->with('success', 'Payment method recorded for invoice #' . $invoice->InvoiceID . '.');
    }

    /**
     * Form to create a new invoice. Can be generated from a specific appointment (?appointment_id=..)
     * to automatically calculate the total from the booked services, and lists currently
     * active promotions so the receptionist can apply a discount.
     */
    public function create(Request $request)
{
    // Only appointments that are still open (not Cancelled) and don't
    // already have an invoice can be billed.
    $appointments = Appointment::with('customer')
        ->where('Status', '!=', 'Cancelled')
        ->doesntHave('invoice')
        ->orderBy('AppointmentDate', 'desc')
        ->get();

    $selectedAppointment = null;
    $totalAmount = 0;
    $discountAmount = 0;
    $finalAmount = 0;
    $lines = collect();

    if ($request->filled('appointment_id')) {

        $selectedAppointment = Appointment::with([
            'customer',
            'services.service'
        ])->find($request->appointment_id);

        if ($selectedAppointment) {

            // Nếu appointment đã có invoice
            $existingInvoice = Invoice::where(
                'AppointmentID',
                $selectedAppointment->AppointmentID
            )->first();

            if ($existingInvoice) {
                return redirect()
                    ->route('receptionist.invoices.show', $existingInvoice)
                    ->with('success', 'This appointment already has an invoice.');
            }

            // Cancelled appointment không được lập invoice
            if ($selectedAppointment->Status === 'Cancelled') {
                return redirect()
                    ->route('receptionist.invoices.create')
                    ->with(
                        'error',
                        'This appointment is cancelled and cannot be invoiced.'
                    );
            }

            // Tính subtotal / discount / final tự động - mỗi dịch vụ tự áp
            // dụng khuyến mãi (nếu có) của chính nó, không giới hạn 1 dòng.
            $totals = $this->calculateInvoiceTotals($selectedAppointment);
            $lines = collect($totals['lines']);
            $totalAmount = $totals['totalAmount'];
            $discountAmount = $totals['discountAmount'];
            $finalAmount = $totals['finalAmount'];
        }
    }

    return view(
        'receptionist.invoices.create',
        compact(
            'appointments',
            'selectedAppointment',
            'totalAmount',
            'discountAmount',
            'finalAmount',
            'lines'
        )
    );
}

    /**
     * Save a new invoice.
     */
   public function store(Request $request)
{
    $validated = $request->validate([
        'AppointmentID' => 'required|exists:Appointment,AppointmentID',
        'PaymentMethod' => 'nullable|string|max:30',
    ]);

    $appointment = Appointment::with('services.service')
        ->find($validated['AppointmentID']);

    if (!$appointment) {
        return back()
            ->withInput()
            ->with('error', 'Appointment not found.');
    }

    $alreadyExists = Invoice::where(
        'AppointmentID',
        $validated['AppointmentID']
    )->exists();

    if ($alreadyExists) {
        return back()
            ->withInput()
            ->with('error', 'This appointment already has an invoice.');
    }

    if ($appointment->Status === 'Cancelled') {
        return back()
            ->withInput()
            ->with('error', 'This appointment is cancelled and cannot be invoiced.');
    }

    if ($appointment->services->isEmpty()) {
        return back()
            ->withInput()
            ->with(
                'error',
                'This appointment has no services booked yet - add at least one service before invoicing it.'
            );
    }

    // Subtotal / discount / final được tính hoàn toàn ở server, mỗi dịch vụ
    // tự áp dụng khuyến mãi đang active của chính nó - không nhận số liệu
    // từ client để tránh bị sửa tay qua form.
    $totals = $this->calculateInvoiceTotals($appointment);

    $validated['CustomerID'] = $appointment->CustomerID ?? null;
    $validated['InvoiceDate'] = now();
    $validated['TotalAmount'] = $totals['totalAmount'];
    $validated['DiscountAmount'] = $totals['discountAmount'];
    $validated['FinalAmount'] = $totals['finalAmount'];

    $invoice = \Illuminate\Support\Facades\DB::transaction(
        function () use ($validated, $appointment) {

            $invoice = Invoice::create($validated);

            $this->syncInvoiceDetails(
                $invoice,
                $appointment
            );

            $appointment->update([
                'Status' => 'Completed'
            ]);

            return $invoice;
        }
    );

    return redirect()
        ->route('receptionist.invoices.show', $invoice)
        ->with(
            'success',
            'Invoice created and appointment marked as completed.'
        );
}
    /**
     * Rebuild InvoiceDetail rows for an invoice from the services booked on
     * its appointment. Subtotal is a computed column (Quantity * UnitPrice)
     * on the SQL Server side — never assigned here.
     */
    private function syncInvoiceDetails(Invoice $invoice, ?Appointment $appointment): void
    {
        InvoiceDetail::where('InvoiceID', $invoice->InvoiceID)->delete();

        if (!$appointment) {
            return;
        }

        foreach ($appointment->services as $line) {
            InvoiceDetail::create([
                'InvoiceID' => $invoice->InvoiceID,
                'ServiceID' => $line->ServiceID,
                'Quantity' => $line->Quantity,
                'UnitPrice' => $line->UnitPrice,
            ]);
        }
    }

    /**
     * View invoice details.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['appointment.customer', 'customer']);

        $details = InvoiceDetail::with('service')
            ->where('InvoiceID', $invoice->InvoiceID)
            ->get();

        return view('receptionist.invoices.show', compact('invoice', 'details'));
    }

    /**
     * Edit an existing invoice's own details (payment method / discount).
     * The appointment it's tied to is fixed - editing never lets the
     * receptionist repoint this invoice at a different appointment, which
     * would amount to creating a second invoice elsewhere.
     */
    public function edit(Invoice $invoice)
    {
        $invoice->load(['appointment.customer', 'appointment.services.service']);

        $lines = collect();
        $totalAmount = (float) $invoice->TotalAmount;
        $discountAmount = (float) $invoice->DiscountAmount;
        $finalAmount = (float) $invoice->FinalAmount;

        if ($invoice->appointment) {
            $totals = $this->calculateInvoiceTotals($invoice->appointment);
            $lines = collect($totals['lines']);
            $totalAmount = $totals['totalAmount'];
            $discountAmount = $totals['discountAmount'];
            $finalAmount = $totals['finalAmount'];
        }

        return view('receptionist.invoices.edit', compact('invoice', 'totalAmount', 'discountAmount', 'finalAmount', 'lines'));
    }

    /**
     * Update an existing invoice. AppointmentID is intentionally not
     * accepted here - it is never taken from the request, so this can only
     * ever edit the invoice it was opened for, never move it to (or create
     * one for) a different appointment.
     */
   public function update(Request $request, Invoice $invoice)
{
    $validated = $request->validate([
        'PaymentMethod' => 'nullable|string|max:30',
    ]);

    $invoice->load([
        'appointment.services.service'
    ]);

    // Tính lại subtotal/discount/final hoàn toàn ở server - mỗi dịch vụ tự
    // áp dụng khuyến mãi đang active của chính nó, giống lúc tạo hoá đơn.
    $totalAmount = 0;
    $discountAmount = 0;
    $finalAmount = 0;

    if ($invoice->appointment) {
        $totals = $this->calculateInvoiceTotals($invoice->appointment);
        $totalAmount = $totals['totalAmount'];
        $discountAmount = $totals['discountAmount'];
        $finalAmount = $totals['finalAmount'];
    }

    $invoice->update([
        'TotalAmount' => $totalAmount,
        'DiscountAmount' => $discountAmount,
        'FinalAmount' => $finalAmount,
        'PaymentMethod' => $validated['PaymentMethod'] ?? null,
    ]);

    return redirect()
        ->route('receptionist.invoices.show', $invoice)
        ->with(
            'success',
            'Invoice #' . $invoice->InvoiceID . ' updated.'
        );
}

    public function destroy(Invoice $invoice)
    {
        return redirect()
            ->route('receptionist.invoices.show', $invoice)
            ->with('error', 'This invoice has already been paid and cannot be deleted.');
    }
}