<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentService;
use App\Models\Customer;
use App\Models\Service;
use Carbon\Carbon;

use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
         $services = Service::where('ServiceType',0)
                ->where('IsActive',1)
                ->orderBy('Category')
                ->get();

    return view('booking.index',compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     * Xử lý dữ liệu gửi lên từ form đặt lịch ở trang chủ (#booking).
     */
    public function store(Request $request)
  {
        $request->validate([

        'fullname' => 'required|max:100',

        'phone' => 'required|max:20',

        'service' => 'required|exists:Service,ServiceID',

        'appointment_date' => 'required|date',

        'appointment_time' => 'required',

        'note' => 'nullable|max:500'

    ]);

    $customer = Customer::where('Phone', $request->phone)->first();

    if (!$customer) {

        $customer = Customer::create([

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

    $service = Service::findOrFail($request->service);

    $start = Carbon::parse($request->appointment_time);

    $end = $start->copy()->addMinutes((int)($service->DurationMinutes));

    $exists = Appointment::where('CustomerID', $customer->CustomerID)
    ->where('AppointmentDate', $request->appointment_date)
    ->where('StartTime', $start->format('H:i:s'))
    ->whereIn('Status', ['Pending', 'Confirmed'])
    ->exists();

    if ($exists) {
        return back()
            ->withInput()
            ->with('error', 'You already have an appointment at this time.');
    }

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



    return redirect()
            ->route('booking.success',  $appointment->AppointmentID);
    }

    public function success($id)
    {
        $appointment = Appointment::findOrFail($id);

        return view('booking.success', compact('appointment'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        //
    }
}