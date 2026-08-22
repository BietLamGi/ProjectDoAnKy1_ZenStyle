<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;

use App\Models\Appointment;
use App\Models\AppointmentService;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentServiceController extends Controller
{
    /**
     * Add a service to an appointment (AppointmentService).
     */
    public function store(Request $request, Appointment $appointment)
    {
        if (in_array($appointment->Status, AppointmentController::LOCKED_STATUSES, true)) {
            return back()->with('error', 'This appointment is "' . $appointment->Status . '" - services can no longer be changed.');
        }

        $validated = $request->validate([
            'ServiceID' => 'required|exists:Service,ServiceID',
            'Quantity' => 'required|integer|min:1',
        ]);

        $service = Service::findOrFail($validated['ServiceID']);

        // Adding a service makes the appointment longer, so EndTime has to grow
        // with it - recompute it *before* saving and, if that pushes into
        // another booking for the same staff, reject the add instead of
        // silently creating an overlap.
        $addedMinutes = (int) $service->DurationMinutes * $validated['Quantity'];
        $newEnd = $this->recalculatedEndTime($appointment, $addedMinutes);

        if ($appointment->StaffID) {
            $conflict = $this->findStaffConflict(
                $appointment->StaffID,
                $appointment->AppointmentDate,
                $appointment->StartTime,
                $newEnd,
                $appointment->AppointmentID
            );

            if ($conflict) {
                return back()->with('error', 'Adding "' . $service->ServiceName . '" would push this appointment into staff\'s booking from '
                    . Carbon::parse($conflict->StartTime)->format('H:i') . ' to '
                    . Carbon::parse($conflict->EndTime)->format('H:i') . '. Choose another service, staff, or time first.');
            }
        }

        DB::transaction(function () use ($appointment, $service, $validated, $newEnd) {
            AppointmentService::create([
                'AppointmentID' => $appointment->AppointmentID,
                'ServiceID' => $service->ServiceID,
                'Quantity' => $validated['Quantity'],
                'UnitPrice' => $service->Price,
            ]);

            $appointment->update(['EndTime' => $newEnd]);
        });

        return back()->with('success', 'Service "' . $service->ServiceName . '" added to the appointment.');
    }

    /**
     * Remove a service from an appointment.
     */
    public function destroy(Appointment $appointment, AppointmentService $appointmentService)
    {
        abort_if($appointmentService->AppointmentID != $appointment->AppointmentID, 404);

        if (in_array($appointment->Status, AppointmentController::LOCKED_STATUSES, true)) {
            return back()->with('error', 'This appointment is "' . $appointment->Status . '" - services can no longer be changed.');
        }

        DB::transaction(function () use ($appointment, $appointmentService) {
            $appointmentService->delete();

            // Shortening never creates a conflict, so just recompute straight away.
            $newEnd = $this->recalculatedEndTime($appointment, 0);
            $appointment->update(['EndTime' => $newEnd]);
        });

        return back()->with('success', 'Service removed from the appointment.');
    }

    /**
     * New EndTime = StartTime + total duration of every service still booked
     * on this appointment (including $extraMinutes for a service about to be
     * added, before it's actually saved). If the appointment ends up with no
     * service lines at all - e.g. it was created directly from Admin, which
     * doesn't book any AppointmentService - keep whatever EndTime it already
     * has instead of collapsing it to a zero-length slot.
     */
    private function recalculatedEndTime(Appointment $appointment, int $extraMinutes = 0): string
    {
        $appointment->loadMissing('services.service');

        $bookedMinutes = $appointment->services->sum(function ($line) {
            return (int) (optional($line->service)->DurationMinutes ?? 0) * (int) $line->Quantity;
        });

        $totalMinutes = $bookedMinutes + $extraMinutes;

        if ($totalMinutes <= 0) {
            return $appointment->EndTime;
        }

        return Carbon::parse($appointment->StartTime)
            ->addMinutes($totalMinutes)
            ->format('H:i:s');
    }

    /**
     * Same overlap rule as AppointmentController::findStaffConflict() - kept
     * local here so this controller doesn't need to reach into a private
     * method on another controller.
     */
    private function findStaffConflict($staffId, $date, $start, $end, $excludeAppointmentId = null)
    {
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
}