<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentService;
use App\Models\Customer;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    public function create(Request $request)
    {
        $services = Service::where('ServiceType', 0)
            ->where('IsActive', 1)
            ->orderBy('Category')
            ->get();

        $customer = null;

        if (Auth::check()) {
            $customer = Customer::where('UserID', Auth::id())->first();
        }

        $selectedService = $request->service;

        return view('customer.booking.index', compact('services', 'customer', 'selectedService'));
    }

    /**
     * Store a newly created appointment.
     */
    public function store(Request $request)
    {
        /*
        | 1. Validate booking form
        */

        $request->validate([
            'fullname' => ['required', 'string', 'max:100'],

            'phone' => [
                'required',
                'string',
                'max:20'
            ],

            'service' => [
                'required',
                'exists:Service,ServiceID'
            ],

            'appointment_date' => [
                'required',
                'date',
                'after_or_equal:today'
            ],

            'appointment_time' => [
                'required'
            ],

            'note' => [
                'nullable',
                'string',
                'max:500'
            ],

        ], [
            'fullname.required' => 'Please enter your full name.',

            'phone.required' => 'Please enter your phone number.',

            'service.required' => 'Please select a service.',

            'service.exists' => 'The selected service is invalid.',

            'appointment_date.required' => 'Please select an appointment date.',

            'appointment_date.after_or_equal' =>
                'Appointment date must be today or later.',

            'appointment_time.required' =>
                'Please select an appointment time.',
        ]);


        /*
        | 2. Get selected service
        */

        $service = Service::findOrFail($request->service);


        /*
        | 3. Calculate Start Time and End Time
        */

        $start = Carbon::parse($request->appointment_time);

        // DurationMinutes có thể được SQL Server trả về dạng string
        // nên ép sang integer trước khi addMinutes().
        $duration = (int) $service->DurationMinutes;

        $end = $start->copy()->addMinutes($duration);


        /*
|--------------------------------------------------------------------------
| 4. Find Customer
|--------------------------------------------------------------------------
|
| CASE 1:
| User đã đăng nhập
| → tìm Customer bằng UserID
|
| CASE 2:
| User chưa đăng nhập (Guest)
| → tìm Customer bằng Phone
| → nếu chưa có thì tạo Customer
|
*/

if (Auth::check()) {

    // User đã login
    // Customer đã được tạo khi Register
    $customer = Customer::where(
        'UserID',
        Auth::id()
    )->firstOrFail();

} else {

    // Guest
    // Tìm Customer bằng Phone
    $customer = Customer::where(
        'Phone',
        $request->phone
    )->first();

    // Guest chưa từng book
    if (!$customer) {

        $customer = Customer::create([
            'UserID' => null,
            'FullName' => $request->fullname,
            'Phone' => $request->phone,
            'Email' => null,
            
            'DOB' => null,
            'Allergies' => null,
            'Notes' => null,
            'LoyaltyPoints' => 0,
            'MembershipTier' => 'Normal',
        ]);
    }
}


        //  5. Check duplicate appointment
        // Một Customer không được đặt 2 lịch cùng thời gian.
        
        $existingAppointment = Appointment::where(
            'CustomerID',
            $customer->CustomerID
        )
            ->where(
                'AppointmentDate',
                $request->appointment_date
            )
            ->whereIn(
                'Status',
                ['Pending', 'Confirmed']
            )
            ->where(function ($query) use ($start, $end) {

                $query->where(
                    'StartTime',
                    '<',
                    $end->format('H:i:s')
                )
                ->where(
                    'EndTime',
                    '>',
                    $start->format('H:i:s')
                );

            })
            ->first();


        if ($existingAppointment) {

            return back()
                ->withInput()
                ->withErrors([
                    'appointment_time' =>
                        'You already have an appointment at this time.'
                ]);
        }


        //  6. Create Appointment + AppointmentService
        

        $appointment = DB::transaction(function () use (
    $request,
    $customer,
    $service,
    $start,
    $end
) {

    $appointment = Appointment::create([
        'CustomerID' => $customer->CustomerID,
        'StaffID' => null,
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

    return $appointment;
});

        return view('customer.booking.success', compact('appointment'));
    }

    public function myAppointments()
    {
        $user = auth()->user();

        // Tìm Customer của User đang đăng nhập
        $customer = Customer::where('UserID', $user->UserID)->first();

        // Nếu User chưa từng đặt lịch
        if (!$customer) {
            return view('customer.appointments.my', [
                'appointments' => collect()
            ]);
        }

        // Lấy các lịch của Customer này
        $appointments = Appointment::where('CustomerID', $customer->CustomerID)
        ->with('services.service')
        ->orderBy('AppointmentDate', 'desc')
        ->orderBy('StartTime', 'desc')
        ->get();

        return view('customer.appointments.my', compact('appointments'));
    }

    public function showMyAppointment($id)
{
    $user = auth()->user();

    $customer = Customer::where('UserID', $user->UserID)->first();

    if (!$customer) {
        abort(404);
    }

    $appointment = Appointment::where('AppointmentID', $id)
        ->where('CustomerID', $customer->CustomerID)
        ->with('services.service')
        ->firstOrFail();

    return view('customer.appointments.show', compact('appointment'));
}

    public function cancel($id)
{
    $user = auth()->user();

    // Tìm Customer của User đang đăng nhập
    $customer = Customer::where('UserID', $user->UserID)->first();

    // Không có Customer
    if (!$customer) {
        return redirect()
            ->route('appointments.my')
            ->with('error', 'Customer information not found.');
    }

    // Chỉ lấy appointment thuộc Customer đang đăng nhập
    $appointment = Appointment::where('AppointmentID', $id)
        ->where('CustomerID', $customer->CustomerID)
        ->firstOrFail();

    // Chỉ được cancel khi đang Pending
    if ($appointment->Status !== 'Pending') {
        return redirect()
            ->route('customer.appointments.show', $appointment->AppointmentID)
            ->with('error', 'This appointment cannot be cancelled.');
    }

    // Cancel
    $appointment->Status = 'Cancelled';
    $appointment->save();

    return redirect()
        ->route('appointments.my')
        ->with('success', 'Appointment cancelled successfully.');

}}