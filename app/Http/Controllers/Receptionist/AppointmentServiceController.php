<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentService;
use App\Models\Service;
use Illuminate\Http\Request;

class AppointmentServiceController extends Controller
{
    /**
     * Thêm 1 dịch vụ vào lịch hẹn (AppointmentService).
     */
    public function store(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'ServiceID' => 'required|exists:Service,ServiceID',
            'Quantity' => 'required|integer|min:1',
        ]);

        $service = Service::findOrFail($validated['ServiceID']);

        AppointmentService::create([
            'AppointmentID' => $appointment->AppointmentID,
            'ServiceID' => $service->ServiceID,
            'Quantity' => $validated['Quantity'],
            'UnitPrice' => $service->Price,
        ]);

        return back()->with('success', 'Đã thêm dịch vụ "' . $service->ServiceName . '" vào lịch hẹn.');
    }

    /**
     * Xoá 1 dịch vụ khỏi lịch hẹn.
     */
    public function destroy(Appointment $appointment, AppointmentService $appointmentService)
    {
        abort_if($appointmentService->AppointmentID != $appointment->AppointmentID, 404);

        $appointmentService->delete();

        return back()->with('success', 'Đã xoá dịch vụ khỏi lịch hẹn.');
    }
}
