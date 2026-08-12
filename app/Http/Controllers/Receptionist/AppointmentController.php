<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentService;
use App\Models\Customer;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public const STATUSES = ['Pending', 'Confirmed', 'CheckedIn', 'Completed', 'Cancelled'];

    /**
     * Danh sách lịch hẹn - lọc theo ngày & trạng thái, tìm theo khách hàng.
     */
    public function index(Request $request)
    {
        $date = $request->query('date', Carbon::today()->toDateString());
        $status = $request->query('status');
        $keyword = $request->query('q');

        $appointments = Appointment::with(['customer', 'services.service'])
            ->when($date, fn ($query) => $query->where('AppointmentDate', $date))
            ->when($status, fn ($query) => $query->where('Status', $status))
            ->when($keyword, function ($query) use ($keyword) {
                $query->whereHas('customer', function ($q) use ($keyword) {
                    $q->where('FullName', 'like', "%{$keyword}%")
                        ->orWhere('Phone', 'like', "%{$keyword}%");
                });
            })
            ->orderBy('StartTime')
            ->paginate(10)
            ->withQueryString();

        return view('receptionist.appointments.index', compact('appointments', 'date', 'status', 'keyword'));
    }

    /**
     * Form tạo lịch hẹn tại quầy (walk-in / đặt hộ khách qua điện thoại).
     */
    public function create()
    {
        $customers = Customer::orderBy('FullName')->get();
        $services = Service::where('ServiceType', 0)
            ->where('IsActive', 1)
            ->orderBy('Category')
            ->get();

        return view('receptionist.appointments.create', compact('customers', 'services'));
    }

    /**
     * Lưu lịch hẹn mới do lễ tân tạo.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:Customer,CustomerID',
            'fullname' => 'nullable|required_without:customer_id|max:100',
            'phone' => 'nullable|required_without:customer_id|max:20',
            'service_id' => 'required|exists:Service,ServiceID',
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

        $appointment = Appointment::create([
            'CustomerID' => $customer->CustomerID,
            'StaffID' => null,
            'AppointmentDate' => $request->appointment_date,
            'StartTime' => $start->format('H:i:s'),
            'EndTime' => $end->format('H:i:s'),
            'Status' => 'Confirmed',
            'Notes' => $request->note,
        ]);

        AppointmentService::create([
            'AppointmentID' => $appointment->AppointmentID,
            'ServiceID' => $service->ServiceID,
            'Quantity' => 1,
            'UnitPrice' => $service->Price,
        ]);

        return redirect()
            ->route('receptionist.appointments.index', ['date' => $request->appointment_date])
            ->with('success', 'Đã tạo lịch hẹn cho khách "' . $customer->FullName . '".');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['customer', 'services.service']);

        return view('receptionist.appointments.show', compact('appointment'));
    }

    /**
     * Form chỉnh sửa ghi chú / trạng thái lịch hẹn.
     */
    public function edit(Appointment $appointment)
    {
        $appointment->load(['customer', 'services.service']);

        $services = \App\Models\Service::where('IsActive', 1)
            ->orderBy('ServiceName')
            ->get();

        return view('receptionist.appointments.edit', [
            'appointment' => $appointment,
            'statuses' => self::STATUSES,
            'services' => $services,
        ]);
    }

    /**
     * Cập nhật lịch hẹn (giờ, ghi chú, trạng thái).
     */
    public function update(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'AppointmentDate' => 'required|date',
            'StartTime' => 'required',
            'Status' => 'required|in:' . implode(',', self::STATUSES),
            'Notes' => 'nullable|max:500',
        ]);

        $appointment->update($data);

        return redirect()
            ->route('receptionist.appointments.index')
            ->with('success', 'Đã cập nhật lịch hẹn #' . $appointment->AppointmentID . '.');
    }

    /**
     * Cập nhật nhanh trạng thái (Xác nhận / Check-in / Hoàn tất / Huỷ) từ danh sách.
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', self::STATUSES),
        ]);

        $appointment->update(['Status' => $request->status]);

        return back()->with('success', 'Đã chuyển lịch hẹn #' . $appointment->AppointmentID . ' sang trạng thái "' . $request->status . '".');
    }

    /**
     * Huỷ / xoá lịch hẹn.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->update(['Status' => 'Cancelled']);

        return redirect()
            ->route('receptionist.appointments.index')
            ->with('success', 'Đã huỷ lịch hẹn #' . $appointment->AppointmentID . '.');
    }
}
