<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\WorkSchedule;

/**
 * Receptionist scope only: view everyone's shifts (read-only, to plan
 * appointments around technician availability) and check-in/check-out.
 * Creating/editing/deleting other staff's schedule freely belongs to
 * Admin/HR and is intentionally not exposed here.
 */
class WorkScheduleController extends Controller
{
    /**
     * Staff work schedule list (read-only).
     */
    public function index()
    {
        $schedules = WorkSchedule::with('user')
            ->orderBy('WorkDate', 'desc')
            ->orderBy('ShiftStart')
            ->paginate(10);

        return view('receptionist.work-schedules.index', compact('schedules'));
    }

    /**
     * Check-in for a shift.
     *
     * Only allowed on the shift's own WorkDate - a receptionist should not
     * be able to check someone in for a shift that's days in the future
     * (or long past); doing so silently recorded today's real timestamp
     * against that unrelated future/past WorkDate, which produced
     * nonsensical rows (e.g. checked in "today" for a shift 4 days away).
     */
    public function checkIn(WorkSchedule $workSchedule)
    {
        if (!$workSchedule->WorkDate->isToday()) {
            return back()->with(
                'error',
                'You can only check in on the day of the shift itself (' . $workSchedule->WorkDate->format('d/m/Y') . ').'
            );
        }

        $workSchedule->update([
            'ActualCheckIn' => now(),
        ]);

        return back()->with('success', 'Checked in at ' . now()->format('H:i') . '.');
    }

    /**
     * Check-out for a shift.
     *
     * Same same-day restriction as checkIn() - and requires an existing
     * check-in first, since a check-out with no check-in has nothing to
     * measure against.
     *
     * WorkedHours is a COMPUTED column on the SQL Server side (derived from
     * ActualCheckIn/ActualCheckOut) - it cannot be written to directly, or
     * SQL Server rejects the UPDATE. Only ActualCheckOut/Status are set
     * here; WorkedHours updates itself automatically once ActualCheckOut is
     * saved.
     */
    public function checkOut(WorkSchedule $workSchedule)
    {
        if (!$workSchedule->WorkDate->isToday()) {
            return back()->with(
                'error',
                'You can only check out on the day of the shift itself (' . $workSchedule->WorkDate->format('d/m/Y') . ').'
            );
        }

        if (!$workSchedule->ActualCheckIn) {
            return back()->with('error', 'This staff member has not checked in yet.');
        }

        $workSchedule->update([
            'ActualCheckOut' => now(),
            'Status' => 'Completed',
        ]);

        return back()->with('success', 'Checked out at ' . now()->format('H:i') . '.');
    }
}