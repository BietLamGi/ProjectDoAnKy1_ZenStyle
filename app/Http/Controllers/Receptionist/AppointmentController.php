<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;

use App\Models\Appointment;
use App\Models\AppointmentService;
use App\Models\Customer;
use App\Models\Notification;
use App\Models\Service;
use App\Models\User;
use App\Models\WorkSchedule;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    public const STATUSES = ['Pending', 'Confirmed', 'CheckedIn', 'Completed', 'Cancelled'];

    /**
     * Statuses that lock the appointment: no more edits, status changes,
     * service add/remove, or cancellation. "Completed" is never set by hand
     * from this list - it's only reached automatically when an invoice is
     * paid (see InvoiceController::store()).
     */
    public const LOCKED_STATUSES = ['Completed', 'Cancelled'];

    /**
     * Manual status changes the receptionist is allowed to make from the
     * list/edit screens, keyed by the current status. "Completed" never
     * appears here - it is only reached automatically once an invoice is
     * created for the appointment.
     */
    public const ALLOWED_TRANSITIONS = [
        'Pending' => ['Confirmed', 'Cancelled'],
        'Confirmed' => ['CheckedIn', 'Cancelled'],
        'CheckedIn' => ['Cancelled'],
        'Completed' => [],
        'Cancelled' => [],
    ];

    /**
     * RoleID used for technician / service staff accounts. Per the DB
     * constraint CK_User_Position: RoleID=2 -> Position='Receptionist',
     * RoleID=3 -> Position='Staff'. Must stay 3.
     */
    public const STAFF_ROLE_ID = 3;

    /**
     * Granularity for suggested start times.
     */
    private const SLOT_STEP_MINUTES = 30;

    private function isLocked(Appointment $appointment): bool
    {
        return in_array($appointment->Status, self::LOCKED_STATUSES, true);
    }

    /**
     * Appointment list - filter by date & status, search by customer.
     *
     * View modes ('view' query param):
     *  - 'day' (default when a date is picked): exact-date match, same as before.
     *  - 'week' / 'month': range around the picked date (or today, if none
     *    picked) so the receptionist can see the whole week/month at once.
     *  - No date at all and view='day': show every appointment (all dates)
     *    instead of silently forcing "today" - unchanged from before.
     */
    public function index(Request $request)
    {
        $date = $request->query('date');
        $status = $request->query('status');
        $keyword = $request->query('q');
        $view = in_array($request->query('view'), ['day', 'week', 'month'], true)
            ? $request->query('view')
            : 'day';

        $rangeStart = null;
        $rangeEnd = null;

        if ($view === 'week') {
            $reference = $date ? Carbon::parse($date) : Carbon::today();
            $rangeStart = $reference->copy()->startOfWeek()->toDateString();
            $rangeEnd = $reference->copy()->endOfWeek()->toDateString();
        } elseif ($view === 'month') {
            $reference = $date ? Carbon::parse($date) : Carbon::today();
            $rangeStart = $reference->copy()->startOfMonth()->toDateString();
            $rangeEnd = $reference->copy()->endOfMonth()->toDateString();
        }

        $appointments = Appointment::with(['customer', 'staff', 'services.service'])
            ->when($rangeStart, fn ($query) => $query->whereBetween('AppointmentDate', [$rangeStart, $rangeEnd]))
            ->when(!$rangeStart && $date, fn ($query) => $query->where('AppointmentDate', $date))
            ->when($status, fn ($query) => $query->where('Status', $status))
            ->when($keyword, function ($query) use ($keyword) {
                $query->whereHas('customer', function ($q) use ($keyword) {
                    $q->where('FullName', 'like', "%{$keyword}%")
                        ->orWhere('Phone', 'like', "%{$keyword}%");
                });
            })
           ->orderByRaw("
    CASE
        WHEN Status = 'Completed' THEN 1
        ELSE 0
    END ASC
")
->orderBy('AppointmentDate', 'desc')
->orderBy('StartTime', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('receptionist.appointments.index', compact(
            'appointments',
            'date',
            'status',
            'keyword',
            'view',
            'rangeStart',
            'rangeEnd'
        ));
    }

    /**
     * Form to create a walk-in appointment at the counter (walk-in customer or phone booking).
     */
    public function create()
    {
        $customers = Customer::orderBy('FullName')->get();
        $services = Service::where('ServiceType', 0)
            ->where('IsActive', 1)
            ->orderBy('Category')
            ->get();
        // No IsActive filter: an inactive account still shows up (marked
        // "(Inactive)" in the view) instead of silently disappearing just
        // because Admin forgot to flip the Active switch when creating it.
        $staffList = User::where('RoleID', self::STAFF_ROLE_ID)
            ->orderBy('Username')
            ->get();

        return view('receptionist.appointments.create', compact('customers', 'services', 'staffList'));
    }

    /**
     * Save a new appointment created by the receptionist.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:Customer,CustomerID',
            'fullname' => 'nullable|required_without:customer_id|max:100',
            'phone' => 'nullable|required_without:customer_id|max:20',
            'service_id' => 'required|exists:Service,ServiceID',
            // exists:User,UserID alone only checks the row exists - it says
            // nothing about RoleID, so without the ->where() scope a request
            // could assign *any* user (a receptionist, an admin, a customer)
            // as staff regardless of what the dropdown on the form shows.
            'staff_id' => [
                'nullable',
                Rule::exists('User', 'UserID')->where(fn ($query) => $query->where('RoleID', self::STAFF_ROLE_ID)),
            ],
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'note' => 'nullable|max:500',
        ]);

        if (empty($request->customer_id) && empty($request->fullname) && empty($request->phone)) {
            return back()->withErrors([
                'customer_id' => 'Please select an existing customer or create a new one by filling in the customer name and phone number.',
            ])->withInput();
        }

        if ($request->filled('customer_id')) {
            $customer = Customer::findOrFail($request->customer_id);
        } else {
            $customer = Customer::where('Phone', $request->phone)->first();

            if (!$customer) {
                $customer = Customer::create([
                    'FullName' => $request->fullname,
                    'Phone' => $request->phone,
                    'LoyaltyPoints' => 0,
                    'MembershipTier' => 'Normal',
                ]);
            }
        }

        $service = Service::findOrFail($request->service_id);

        $start = Carbon::parse($request->appointment_time);
        $end = $start->copy()->addMinutes((int) $service->DurationMinutes);

       if ($request->filled('staff_id')) {

    // Check staff nghỉ phép trong ngày đã chọn (đơn nghỉ đã được duyệt)
    $onLeave = LeaveRequest::where('UserID', $request->staff_id)
        ->where('Status', 'Approved')
        ->whereDate('LeaveStartDate', '<=', $request->appointment_date)
        ->whereDate('LeaveEndDate', '>=', $request->appointment_date)
        ->exists();

    if ($onLeave) {
        return back()->withErrors([
            'staff_id' => 'This staff member is on leave on the selected date. Please choose another staff member.',
        ])->withInput();
    }

    // Check giờ hẹn có nằm trong ca làm (WorkSchedule) của staff không
    if (!$this->isWithinShift($request->staff_id, $request->appointment_date, $start->format('H:i:s'), $end->format('H:i:s'))) {
        return back()->withErrors([
            'staff_id' => 'This staff member is not scheduled to work at the selected time on this date.',
        ])->withInput();
    }

    // Check staff đã có lịch khác bị trùng giờ
    $conflict = $this->findStaffConflict(
        $request->staff_id,
        $request->appointment_date,
        $start->format('H:i:s'),
        $end->format('H:i:s')
    );

    if ($conflict) {
        return back()->withErrors([
            'staff_id' => 'This staff member already has an appointment from '
                . Carbon::parse($conflict->StartTime)->format('H:i') . ' to '
                . Carbon::parse($conflict->EndTime)->format('H:i')
                . ' on this date. Please choose another staff member or time.',
        ])->withInput();
    }
}

        $customerConflict = $this->findCustomerConflict(
            $customer->CustomerID,
            $request->appointment_date,
            $start->format('H:i:s'),
            $end->format('H:i:s')
        );

        if ($customerConflict) {
            return back()->withErrors([
                'customer_id' => 'This customer already has an appointment from '
                    . Carbon::parse($customerConflict->StartTime)->format('H:i') . ' to '
                    . Carbon::parse($customerConflict->EndTime)->format('H:i') . ' on this date.',
            ])->withInput();
        }

        $appointment = Appointment::create([
            'CustomerID' => $customer->CustomerID,
            'StaffID' => $request->staff_id ?: null,
            'AppointmentDate' => $request->appointment_date,
            'StartTime' => $start->format('H:i:s'),
            'EndTime' => $end->format('H:i:s'),
            'Status' => 'Pending',
            'Notes' => $request->note,
        ]);

        AppointmentService::create([
            'AppointmentID' => $appointment->AppointmentID,
            'ServiceID' => $service->ServiceID,
            'Quantity' => 1,
            'UnitPrice' => $service->Price,
        ]);

        $this->notifyBookingConfirmed($appointment, $customer, $service);

        return redirect()
            ->route('receptionist.appointments.index', ['date' => $request->appointment_date])
            ->with('success', 'Appointment created for customer "' . $customer->FullName . '".');
    }

    /**
     * Booking-confirmation notifications: one for the customer (only if they
     * have a linked User login - walk-ins created with just name/phone have
     * no account to notify), and one for the assigned staff member if any.
     * "Received automatically" here means as soon as the appointment is
     * saved, not on a delay - there is no scheduler/queue in this project
     * to send it later.
     */
    private function notifyBookingConfirmed(Appointment $appointment, Customer $customer, Service $service): void
    {
        $when = Carbon::parse($appointment->AppointmentDate)->format('d/m/Y')
            . ' ' . Carbon::parse($appointment->StartTime)->format('H:i');

        if ($customer->UserID) {
            Notification::create([
                'UserID' => $customer->UserID,
                'Title' => 'Appointment confirmed',
                'Message' => 'Your appointment for "' . $service->ServiceName . '" on ' . $when . ' has been confirmed.',
                'Type' => 'AppointmentConfirmation',
                'IsRead' => false,
                'CreatedAt' => now(),
            ]);
        }

        if ($appointment->StaffID) {
            Notification::create([
                'UserID' => $appointment->StaffID,
                'Title' => 'New appointment assigned',
                'Message' => 'You have been assigned "' . $service->ServiceName . '" with ' . $customer->FullName . ' on ' . $when . '.',
                'Type' => 'StaffAssignment',
                'IsRead' => false,
                'CreatedAt' => now(),
            ]);
        }
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['customer', 'staff', 'services.service']);

        return view('receptionist.appointments.show', compact('appointment'));
    }

    /**
     * Form to edit an appointment's date/time, assigned staff, notes, and status.
     */
    public function edit(Appointment $appointment)
    {
        if ($this->isLocked($appointment)) {
            return redirect()
                ->route('receptionist.appointments.show', $appointment)
                ->with('error', 'This appointment is "' . $appointment->Status . '" and can no longer be edited - view only.');
        }

        $appointment->load(['customer', 'staff', 'services.service']);

        $services = Service::where('IsActive', 1)
            ->orderBy('ServiceName')
            ->get();

        // Same as create(): no IsActive filter, so an inactive staff account
        // still shows in the dropdown (marked "(Inactive)") instead of
        // disappearing.
        $staffList = User::where('RoleID', self::STAFF_ROLE_ID)
            ->orderBy('Username')
            ->get();

        return view('receptionist.appointments.edit', [
            'appointment' => $appointment,
            'services' => $services,
            'staffList' => $staffList,
        ]);
    }

    /**
     * Update an appointment (date, time, assigned staff, notes, status).
     */
    public function update(Request $request, Appointment $appointment)
    {
        if ($this->isLocked($appointment)) {
            return redirect()
                ->route('receptionist.appointments.show', $appointment)
                ->with('error', 'This appointment is "' . $appointment->Status . '" and can no longer be edited - view only.');
        }

        $data = $request->validate([
            'AppointmentDate' => 'required|date',
            'StartTime' => 'required',
            // Same fix as store(): scope to RoleID=Staff so a request can't
            // assign a receptionist/admin/customer account as staff just
            // because exists:User,UserID doesn't check RoleID.
            'StaffID' => [
                'nullable',
                Rule::exists('User', 'UserID')->where(fn ($query) => $query->where('RoleID', self::STAFF_ROLE_ID)),
            ],
            'Status' => 'required|in:' . implode(',', self::STATUSES),
            'Notes' => 'nullable|max:500',
        ]);

        // Only allow the status to move along the permitted path for the
        // appointment's current status (e.g. Pending -> Confirmed/Cancelled).
        // "Completed" can never be picked here - it's only set automatically
        // once an invoice is created and paid.
        $allowed = self::ALLOWED_TRANSITIONS[$appointment->Status] ?? [];
        if ($data['Status'] !== $appointment->Status && !in_array($data['Status'], $allowed, true)) {
            return back()->withErrors([
                'Status' => 'Cannot change status from "' . $appointment->Status . '" to "' . $data['Status'] . '" directly.',
            ])->withInput();
        }

        $data['StaffID'] = $data['StaffID'] ?? null;

        // EndTime is never taken from user input. Recompute it from the total
        // duration of the services actually booked on this appointment (an
        // appointment can carry more than one service - see
        // AppointmentServiceController). Some appointments have no
        // AppointmentService rows at all - e.g. ones created directly from
        // Admin, which doesn't book any service - so fall back to the
        // appointment's existing StartTime/EndTime gap in that case instead
        // of collapsing it to zero.
        $appointment->loadMissing('services.service');
        $durationMinutes = $appointment->services->sum(function ($line) {
            return (int) (optional($line->service)->DurationMinutes ?? 0) * (int) $line->Quantity;
        });

        if ($durationMinutes <= 0) {
            $durationMinutes = Carbon::parse($appointment->StartTime)->diffInMinutes(Carbon::parse($appointment->EndTime));
        }

        $newStart = Carbon::parse($data['StartTime']);
        $newEnd = $newStart->copy()->addMinutes(max($durationMinutes, 0));

        $data['StartTime'] = $newStart->format('H:i:s');
        $data['EndTime'] = $newEnd->format('H:i:s');

        if ($data['StaffID']) {
            $onLeave = LeaveRequest::where('UserID', $data['StaffID'])
                ->where('Status', 'Approved')
                ->whereDate('LeaveStartDate', '<=', $data['AppointmentDate'])
                ->whereDate('LeaveEndDate', '>=', $data['AppointmentDate'])
                ->exists();

            if ($onLeave) {
                return back()->withErrors([
                    'StaffID' => 'This staff member is on leave on the selected date. Please choose another staff member.',
                ])->withInput();
            }

            if (!$this->isWithinShift($data['StaffID'], $data['AppointmentDate'], $data['StartTime'], $data['EndTime'])) {
                return back()->withErrors([
                    'StaffID' => 'This staff member is not scheduled to work at the selected time on this date.',
                ])->withInput();
            }

            $conflict = $this->findStaffConflict(
                $data['StaffID'],
                $data['AppointmentDate'],
                $data['StartTime'],
                $data['EndTime'],
                $appointment->AppointmentID
            );

            if ($conflict) {
                return back()->withErrors([
                    'StaffID' => 'This staff member already has an appointment from '
                        . Carbon::parse($conflict->StartTime)->format('H:i') . ' to '
                        . Carbon::parse($conflict->EndTime)->format('H:i') . ' on this date.',
                ])->withInput();
            }
        }

        $customerConflict = $this->findCustomerConflict(
            $appointment->CustomerID,
            $data['AppointmentDate'],
            $data['StartTime'],
            $data['EndTime'],
            $appointment->AppointmentID
        );

        if ($customerConflict) {
            return back()->withErrors([
                'StartTime' => 'This customer already has another appointment from '
                    . Carbon::parse($customerConflict->StartTime)->format('H:i') . ' to '
                    . Carbon::parse($customerConflict->EndTime)->format('H:i') . ' on this date.',
            ])->withInput();
        }

       $oldStaffId = $appointment->StaffID;

$appointment->update($data);

/*
|--------------------------------------------------------------------------
| Notify staff when receptionist assigns them
|--------------------------------------------------------------------------
*/

if (
    empty($oldStaffId)
    && !empty($data['StaffID'])
) {
    $appointment->load([
        'customer',
        'services.service'
    ]);

    $customer = $appointment->customer;

    $service = optional(
        $appointment->services->first()
    )->service;

    if ($customer && $service) {

        $when = Carbon::parse($appointment->AppointmentDate)
            ->format('d/m/Y')
            . ' '
            . Carbon::parse($appointment->StartTime)
                ->format('H:i');

        Notification::create([
            'UserID' => $data['StaffID'],

            'Title' => 'New appointment assigned',

            'Message' =>
                'You have been assigned "' .
                $service->ServiceName .
                '" with ' .
                $customer->FullName .
                ' on ' .
                $when .
                '.',

            'Type' => 'StaffAssignment',

            'IsRead' => false,

            'CreatedAt' => now(),
        ]);
    }
}

return redirect()
            ->route('receptionist.appointments.index')
            ->with('success', 'Appointment #' . $appointment->AppointmentID . ' updated.');
    }

    /**
     * Quick status update (Confirm / Check-in / Complete / Cancel) from the list view.
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', self::STATUSES),
        ]);

        if ($this->isLocked($appointment)) {
            return back()->with('error', 'Appointment #' . $appointment->AppointmentID . ' is already "' . $appointment->Status . '" and can no longer be changed.');
        }

        $allowed = self::ALLOWED_TRANSITIONS[$appointment->Status] ?? [];
        if (!in_array($request->status, $allowed, true)) {
            return back()->with('error', 'Cannot move appointment #' . $appointment->AppointmentID . ' from "' . $appointment->Status . '" to "' . $request->status . '" directly.');
        }

        $appointment->update(['Status' => $request->status]);

        return back()->with('success', 'Appointment #' . $appointment->AppointmentID . ' moved to status "' . $request->status . '".');
    }

    /**
     * Cancel an appointment.
     */
    public function destroy(Appointment $appointment)
    {
        if ($this->isLocked($appointment)) {
            return redirect()
                ->route('receptionist.appointments.index')
                ->with('error', 'Appointment #' . $appointment->AppointmentID . ' is already "' . $appointment->Status . '" and cannot be cancelled.');
        }

        $appointment->update(['Status' => 'Cancelled']);

        return redirect()
            ->route('receptionist.appointments.index')
            ->with('success', 'Appointment #' . $appointment->AppointmentID . ' has been cancelled.');
    }

    /**
     * JSON endpoint used by the create/edit forms: given a date, time and
     * service (which determines duration), returns which staff are already
     * busy in that slot and whether the selected customer already has a
     * conflicting appointment. Purely for live UI feedback - store()/update()
     * still re-check on the server before saving.
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'appointment_date' => 'required|date',
            'appointment_time' => 'nullable',
            'service_id' => 'nullable|exists:Service,ServiceID',
            'customer_id' => 'nullable|exists:Customer,CustomerID',
            'exclude_id' => 'nullable|integer',
        ]);

        // Time + service are optional so the "no schedule this day" check
        // can run the moment a date is picked, before the receptionist has
        // chosen a service/time. Once both are filled in we also know the
        // exact slot, so busy-staff and staff_id-conflict checks kick in.
        $hasTimeWindow = $request->filled('appointment_time') && $request->filled('service_id');

        $start = $end = null;
        $busyStaffIds = collect();

        if ($hasTimeWindow) {
            $service = Service::findOrFail($request->service_id);
            $start = Carbon::parse($request->appointment_time);
            $end = $start->copy()->addMinutes((int) $service->DurationMinutes);

            $busyQuery = Appointment::where('AppointmentDate', $request->appointment_date)
                ->where('Status', '!=', 'Cancelled')
                ->where('StartTime', '<', $end->format('H:i:s'))
                ->where('EndTime', '>', $start->format('H:i:s'))
                ->whereNotNull('StaffID');

            if ($request->filled('exclude_id')) {
                $busyQuery->where('AppointmentID', '!=', $request->exclude_id);
            }

            $busyStaffIds = $busyQuery->pluck('StaffID')->unique()->values();
        }

        $leaveStaffIds = LeaveRequest::where('Status', 'Approved')
    ->whereDate('LeaveStartDate', '<=', $request->appointment_date)
    ->whereDate('LeaveEndDate', '>=', $request->appointment_date)
    ->pluck('UserID')
    ->unique()
    ->values();

        $activeStaff = User::where('RoleID', self::STAFF_ROLE_ID)
            ->where('IsActive', 1)
            ->get();

        if ($hasTimeWindow) {
            // Exact time window known - check the shift actually covers it.
            $noScheduleStaffIds = $activeStaff
                ->filter(function ($staff) use ($request, $start, $end) {
                    return !$this->isWithinShift(
                        $staff->UserID,
                        $request->appointment_date,
                        $start->format('H:i:s'),
                        $end->format('H:i:s')
                    );
                })
                ->pluck('UserID')
                ->unique()
                ->values();
        } else {
            // Date only - flag staff who have no WorkSchedule row at all
            // for that day (matches availableSlots()'s "no_schedule" check).
            $scheduledStaffIds = WorkSchedule::where('WorkDate', $request->appointment_date)
                ->whereIn('UserID', $activeStaff->pluck('UserID'))
                ->pluck('UserID');

            $noScheduleStaffIds = $activeStaff
                ->pluck('UserID')
                ->diff($scheduledStaffIds)
                ->unique()
                ->values();
        }

        $customerConflictMessage = null;

        if ($hasTimeWindow && $request->filled('staff_id')) {

    // Check approved leave request
    $onLeave = LeaveRequest::where('UserID', $request->staff_id)
        ->where('Status', 'Approved')
        ->whereDate('LeaveStartDate', '<=', $request->appointment_date)
        ->whereDate('LeaveEndDate', '>=', $request->appointment_date)
        ->exists();

    if ($onLeave) {
        return response()->json([
    'busy_staff_ids' => $busyStaffIds,
    'leave_staff_ids' => $leaveStaffIds,
    'no_schedule_staff_ids' => $noScheduleStaffIds,
    'customer_conflict' => $customerConflictMessage,
]);
    }

    // Check existing appointment conflict
    $conflict = $this->findStaffConflict(
        $request->staff_id,
        $request->appointment_date,
        $start->format('H:i:s'),
        $end->format('H:i:s')
    );

    if ($conflict) {
        return back()->withErrors([
            'staff_id' => 'This staff member already has an appointment from '
                . Carbon::parse($conflict->StartTime)->format('H:i')
                . ' to '
                . Carbon::parse($conflict->EndTime)->format('H:i')
                . ' on this date. Please choose another staff member or time.',
        ])->withInput();
    }
}

return response()->json([
    'busy_staff_ids' => $busyStaffIds,
    'leave_staff_ids' => $leaveStaffIds,
    'no_schedule_staff_ids' => $noScheduleStaffIds,
    'customer_conflict' => $customerConflictMessage,
]);
    }

    /**
     * Free time slots for a given service on a given date, broken down per
     * staff member, so the receptionist can pick an open slot directly
     * instead of guessing a time and being told afterwards it's busy.
     *
     * For each active staff member: use their WorkSchedule shift for that
     * date if one exists and they're not on approved leave that day.
     * Staff with no WorkSchedule row for that date are treated as not
     * working - they're returned with `no_schedule: true` and no slots,
     * matching isWithinShift()'s rule for actually saving an appointment.
     */
    public function availableSlots(Request $request)
    {
        $request->validate([
            'appointment_date' => 'required|date',
            'service_id' => 'required|exists:Service,ServiceID',
            'staff_id' => 'nullable|exists:User,UserID',
            'exclude_id' => 'nullable|integer',
        ]);

        $service = Service::findOrFail($request->service_id);

$durationMinutes = max(
    (int) $service->DurationMinutes,
    self::SLOT_STEP_MINUTES
);

$date = $request->appointment_date;

// Lấy danh sách Staff
$staffQuery = User::where('RoleID', self::STAFF_ROLE_ID)
    ->where('IsActive', 1)
    ->orderBy('Username');

if ($request->filled('staff_id')) {
    $staffQuery->where('UserID', $request->staff_id);
}

$staffList = $staffQuery->get();

// Lấy lịch làm việc của Staff trong ngày
$schedules = WorkSchedule::where('WorkDate', $date)
    ->whereIn('UserID', $staffList->pluck('UserID'))
    ->get()
    ->keyBy('UserID');

// Lấy Staff đang nghỉ phép đã được duyệt
$leaveRequests = LeaveRequest::where('Status', 'Approved')
    ->whereDate('LeaveStartDate', '<=', $date)
    ->whereDate('LeaveEndDate', '>=', $date)
    ->whereIn('UserID', $staffList->pluck('UserID'))
    ->pluck('UserID')
    ->flip();

$result = $staffList->map(function ($staff) use (
    $schedules,
    $leaveRequests,
    $date,
    $durationMinutes,
    $request
) {
    $schedule = $schedules->get($staff->UserID);

    // Staff đang nghỉ phép
    if ($leaveRequests->has($staff->UserID)) {
        return [
            'staff_id' => $staff->UserID,
            'name' => $staff->Username,
            'on_leave' => true,
            'no_schedule' => false,
            'slots' => [],
        ];
    }

    // Không có WorkSchedule cho ngày này => không được coi là đang làm việc
    // hôm đó, không gợi ý slot nào (khớp với isWithinShift() dùng khi lưu
    // appointment thật - tránh gợi ý 1 giờ mà lúc submit lại bị từ chối).
    if (!$schedule) {
        return [
            'staff_id' => $staff->UserID,
            'name' => $staff->Username,
            'on_leave' => false,
            'no_schedule' => true,
            'slots' => [],
        ];
    }

    $windowStart = Carbon::parse($schedule->ShiftStart);
    $windowEnd = Carbon::parse($schedule->ShiftEnd);

    // Tìm các slot còn trống
    $slots = $this->freeSlotsForStaff(
        $staff->UserID,
        $date,
        $windowStart,
        $windowEnd,
        $durationMinutes,
        $request->exclude_id
    );

    return [
        'staff_id' => $staff->UserID,
        'name' => $staff->Username,
        'on_leave' => false,
        'no_schedule' => false,
        'slots' => $slots,
    ];
})->values();

return response()->json([
    'duration_minutes' => $durationMinutes,
    'staff' => $result,
]);
    }

    /**
     * Candidate start times (stepped by SLOT_STEP_MINUTES) between
     * $windowStart and $windowEnd that fit the full service duration and
     * don't overlap an existing active appointment for this staff member.
     */
    private function freeSlotsForStaff(int $staffId, string $date, Carbon $windowStart, Carbon $windowEnd, int $durationMinutes, $excludeAppointmentId = null): array
    {
        $busy = Appointment::where('StaffID', $staffId)
            ->where('AppointmentDate', $date)
            ->where('Status', '!=', 'Cancelled')
            ->when($excludeAppointmentId, fn ($query) => $query->where('AppointmentID', '!=', $excludeAppointmentId))
            ->get(['StartTime', 'EndTime']);

        $slots = [];
        $cursor = $windowStart->copy();

        while ($cursor->copy()->addMinutes($durationMinutes)->lte($windowEnd)) {
            $slotStart = $cursor->format('H:i:s');
            $slotEnd = $cursor->copy()->addMinutes($durationMinutes)->format('H:i:s');

            $overlaps = $busy->contains(function ($appointment) use ($slotStart, $slotEnd) {
                return $appointment->StartTime < $slotEnd && $appointment->EndTime > $slotStart;
            });

            if (!$overlaps) {
                $slots[] = $cursor->format('H:i');
            }

            $cursor->addMinutes(self::SLOT_STEP_MINUTES);
        }

        return $slots;
    }

    /**
     * Whether [$start, $end) falls entirely inside the staff member's shift
     * for $date. Requires an explicit WorkSchedule row for that staff/date
     * with a real shift window - if none exists, the staff is treated as
     * NOT scheduled to work that day at all and cannot be assigned, rather
     * than silently falling back to the salon's default hours.
     */
    private function isWithinShift($staffId, $date, $start, $end): bool
    {
        $schedule = WorkSchedule::where('UserID', $staffId)
            ->where('WorkDate', $date)
            ->first();

        if (!$schedule) {
            return false;
        }

        $shiftStart = Carbon::parse($schedule->ShiftStart)->format('H:i:s');
        $shiftEnd = Carbon::parse($schedule->ShiftEnd)->format('H:i:s');

        return $start >= $shiftStart && $end <= $shiftEnd;
    }

    /**
     * First active (non-cancelled) appointment for a staff member that
     * overlaps the given date/time window, if any.
     */
    private function findStaffConflict($staffId, $date, $start, $end, $excludeAppointmentId = null)
    {
        if (!$staffId) {
            return null;
        }

        $query = Appointment::where('StaffID', $staffId)
            ->where('AppointmentDate', $date)
            ->where('Status', '!=', 'Cancelled')
            ->where('StartTime', '<', $end)
            ->where('EndTime', '>', $start);

        if ($excludeAppointmentId) {
            $query->where('AppointmentID', '!=', $excludeAppointmentId);
        }

        return $query->first();
    }

    /**
     * First active (non-cancelled) appointment for a customer that overlaps
     * the given date/time window, if any.
     */
    private function findCustomerConflict($customerId, $date, $start, $end, $excludeAppointmentId = null)
    {
        if (!$customerId) {
            return null;
        }

        $query = Appointment::where('CustomerID', $customerId)
            ->where('AppointmentDate', $date)
            ->where('Status', '!=', 'Cancelled')
            ->where('StartTime', '<', $end)
            ->where('EndTime', '>', $start);

        if ($excludeAppointmentId) {
            $query->where('AppointmentID', '!=', $excludeAppointmentId);
        }

        return $query->first();
    }
}