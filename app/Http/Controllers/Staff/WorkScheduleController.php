<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\WorkSchedule;
use Illuminate\Support\Facades\Auth;

class WorkScheduleController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Lịch làm việc của Staff đang đăng nhập
        $schedules = WorkSchedule::where('UserID', $user->UserID)
            ->orderBy('WorkDate')
            ->orderBy('ShiftStart')
            ->get();

        // Các cuộc hẹn được phân công cho Staff
        $appointments = Appointment::with([
                'customer',
                'services.service'
            ])
            ->where('StaffID', $user->UserID)
            ->orderBy('AppointmentDate')
            ->orderBy('StartTime')
            ->get();

       return view(
    'staff.work-schedule.index',
    compact('schedules', 'appointments')
);
    }
}