<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Promotion;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Invoice list.
     *
     * "Needs follow-up" here covers two different things, since PaymentMethod
     * is nullable and there's no separate "unpaid" state in this system:
     *   1. An Invoice that exists but has no PaymentMethod recorded yet.
     *   2. A booked (non-cancelled) Appointment that has no Invoice at all
     *      yet - it wouldn't otherwise appear anywhere on this page since
     *      it isn't an Invoice row.
     * Both are always shown - "$onlyUnconfirmed" just narrows the Invoice
     * table down to unpaid rows, it never hides anything by default.
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
        // row, so they never show up above. Always surfaced (not just when
        // the "unconfirmed" filter is on) so the receptionist can see every
        // customer still needing a bill in one place, including ones not
        // checked-in yet - those simply can't be invoiced until they are
        // (see store()).
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
    // Only appointments the customer has actually checked in for can be
    // billed - Pending/Confirmed appointments haven't happened yet, so
    // there's nothing to charge for. (Cancelled ones obviously can't
    // either.) See store() for the matching server-side guard.
    $appointments = Appointment::with('customer')
        ->where('Status', 'CheckedIn')
        ->doesntHave('invoice')
        ->orderBy('AppointmentDate', 'desc')
        ->get();

    $selectedAppointment = null;
    $totalAmount = 0;

    // Mặc định không có promotion nào
    $activePromotions = collect();

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

            // Chưa check-in thì chưa có gì để tính tiền - khách chưa tới /
            // chưa được phục vụ.
            if ($selectedAppointment->Status !== 'CheckedIn') {
                return redirect()
                    ->route('receptionist.invoices.create')
                    ->with(
                        'error',
                        'This appointment is "' . $selectedAppointment->Status . '" - the customer must be checked in before it can be invoiced.'
                    );
            }

            // Defensive - CheckedIn should already imply the date has
            // arrived (see AppointmentController::checkInBlockedReason()),
            // but never bill an appointment dated in the future either way.
            if (\Carbon\Carbon::parse($selectedAppointment->AppointmentDate)->startOfDay()->gt(\Carbon\Carbon::today())) {
                return redirect()
                    ->route('receptionist.invoices.create')
                    ->with(
                        'error',
                        'This appointment is scheduled for a future date and cannot be invoiced yet.'
                    );
            }

            // Tính subtotal
            $totalAmount = $selectedAppointment->services->sum(function ($line) {
                return $line->Quantity * $line->UnitPrice;
            });

            /*
             * Lấy ServiceID của những dịch vụ mà appointment này đã book.
             *
             * Ví dụ Appointment #9:
             * ServiceID = 1
             *
             * Thì chỉ lấy Promotion có ServiceID = 1.
             */
            $bookedServiceIds = $selectedAppointment->services
                ->pluck('ServiceID')
                ->unique()
                ->values();

            // Chỉ lấy promotion đang active + đúng dịch vụ
            $activePromotions = $this->activePromotions()
                ->whereIn('ServiceID', $bookedServiceIds)
                ->values();
        }
    }

    return view(
        'receptionist.invoices.create',
        compact(
            'appointments',
            'selectedAppointment',
            'totalAmount',
            'activePromotions'
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
        'PromotionID' => 'nullable|exists:Promotion,PromotionID',
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

    // Nothing to charge for until the customer has actually checked in -
    // blocks paying/invoicing an appointment that's still Pending/Confirmed
    // (customer hasn't arrived / hasn't been served yet). This is also what
    // guards the automatic Status -> 'Completed' below from firing early.
    if ($appointment->Status !== 'CheckedIn') {
        return back()
            ->withInput()
            ->with(
                'error',
                'This appointment is "' . $appointment->Status . '" - the customer must be checked in before it can be invoiced.'
            );
    }

    // Defensive - CheckedIn should already imply the date has arrived, but
    // never bill an appointment dated in the future either way.
    if (\Carbon\Carbon::parse($appointment->AppointmentDate)->startOfDay()->gt(\Carbon\Carbon::today())) {
        return back()
            ->withInput()
            ->with('error', 'This appointment is scheduled for a future date and cannot be invoiced yet.');
    }

    if ($appointment->services->isEmpty()) {
        return back()
            ->withInput()
            ->with(
                'error',
                'This appointment has no services booked yet - add at least one service before invoicing it.'
            );
    }

    // Calculate subtotal from actual booked services
    $totalAmount = (float) $appointment->services->sum(function ($line) {
        return $line->Quantity * $line->UnitPrice;
    });

    // Calculate discount from promotion
    $discountAmount = 0;

    if (!empty($validated['PromotionID'])) {

        // Lấy ServiceID của các dịch vụ mà appointment đã book
        $bookedServiceIds = $appointment->services
            ->pluck('ServiceID')
            ->unique()
            ->values();

        // Chỉ lấy promotion thuộc đúng dịch vụ của appointment
        $promotion = Promotion::where(
                'PromotionID',
                $validated['PromotionID']
            )
            ->where('IsActive', 1)
            ->whereIn('ServiceID', $bookedServiceIds)
            ->first();

        if (!$promotion) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'This promotion cannot be applied to the selected appointment services.'
                );
        }

        if ($promotion->DiscountType === 'Percent') {

            $discountAmount =
                $totalAmount *
                ((float) $promotion->DiscountValue / 100);

        } else {

            $discountAmount =
                (float) $promotion->DiscountValue;
        }

        // Discount cannot exceed subtotal
        $discountAmount = min(
            max(0, $discountAmount),
            $totalAmount
        );
    }

    $finalAmount = max(
        0,
        $totalAmount - $discountAmount
    );

    $validated['CustomerID'] = $appointment->CustomerID ?? null;
    $validated['InvoiceDate'] = now();
    $validated['TotalAmount'] = $totalAmount;
    $validated['DiscountAmount'] = $discountAmount;
    $validated['FinalAmount'] = $finalAmount;

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

        $totalAmount = $invoice->appointment
            ? (float) $invoice->appointment->services->sum(function ($line) {
                return $line->Quantity * $line->UnitPrice;
            })
            : (float) $invoice->TotalAmount;

        $activePromotions = $this->activePromotions();

        if ($invoice->appointment) {
            $bookedServiceIds = $invoice->appointment->services
                ->pluck('ServiceID')
                ->all();

            $activePromotions = $activePromotions->filter(function ($promotion) use ($bookedServiceIds) {
                return is_null($promotion->ServiceID)
                    || in_array($promotion->ServiceID, $bookedServiceIds);
            })->values();
        }

        return view('receptionist.invoices.edit', compact('invoice', 'totalAmount', 'activePromotions'));
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
    'PromotionID' => 'nullable|exists:Promotion,PromotionID',
]);

    $invoice->load([
        'appointment.services'
    ]);

    // Tự tính lại tổng tiền từ dịch vụ
    $totalAmount = $invoice->appointment
        ? (float) $invoice->appointment->services->sum(function ($line) {
            return $line->Quantity * $line->UnitPrice;
        })
        : 0;

    /*
     * Discount lấy từ promotion.
     * Vì hiện tại Invoice chưa lưu PromotionID,
     * nên nếu muốn tự động tính promotion khi Edit,
     * cần gửi PromotionID từ form.
     */

    $discountAmount = 0;

    if ($request->filled('PromotionID')) {

        $promotion = Promotion::where('PromotionID', $request->PromotionID)
            ->where('IsActive', 1)
            ->whereDate('StartDate', '<=', now())
            ->whereDate('EndDate', '>=', now())
            ->first();

        if ($promotion) {

            if ($promotion->DiscountType === 'Percent') {

                $discountAmount =
                    $totalAmount * ((float) $promotion->DiscountValue / 100);

            } else {

                $discountAmount =
                    (float) $promotion->DiscountValue;
            }

            $discountAmount = min(
                max(0, $discountAmount),
                $totalAmount
            );
        }
    }

    $finalAmount = max(
        0,
        $totalAmount - $discountAmount
    );

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

    /**
     * Promotions currently within their active date range, for lookup when billing.
     */
   private function activePromotions()
{
    return Promotion::with('service')
        ->where('IsActive', 1)
        ->orderBy('Title')
        ->get();
}
}