<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkSchedule;
use Illuminate\Http\Request;

class WorkScheduleController extends Controller
{
    /**
     * Danh sách lịch làm việc nhân viên.
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
     * Form tạo lịch làm việc mới.
     */
    public function create()
    {
        $users = User::where('IsActive', 1)
            ->where('RoleID', 2)
            ->orderBy('Username')
            ->get();

        return view('receptionist.work-schedules.create', compact('users'));
    }

    /**
     * Lưu lịch làm việc mới.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'UserID' => 'required|exists:User,UserID',
            'WorkDate' => 'required|date',
            'ShiftStart' => 'required',
            'ShiftEnd' => 'required|after:ShiftStart',
            'ActualCheckIn' => 'nullable|date',
            'ActualCheckOut' => 'nullable|date|after:ActualCheckIn',
            'Status' => 'required|in:Scheduled,OnLeave,Completed',
        ]);

        WorkSchedule::create($validated);

        return redirect()
            ->route('receptionist.work-schedules.index')
            ->with('success', 'Đã thêm lịch làm việc.');
    }

    /**
     * Form sửa lịch làm việc.
     */
    public function edit(WorkSchedule $workSchedule)
    {
        $users = User::where('IsActive', 1)
            ->where('RoleID', 2)
            ->orderBy('Username')
            ->get();

        return view(
            'receptionist.work-schedules.edit',
            compact('workSchedule', 'users')
        );
    }

    /**
     * Cập nhật lịch làm việc (giờ vào/ra thực tế, trạng thái ca làm).
     */
    public function update(Request $request, WorkSchedule $workSchedule)
    {
        $validated = $request->validate([
            'UserID' => 'required|exists:User,UserID',
            'WorkDate' => 'required|date',
            'ShiftStart' => 'required',
            'ShiftEnd' => 'required|after:ShiftStart',
            'ActualCheckIn' => 'nullable|date',
            'ActualCheckOut' => 'nullable|date|after:ActualCheckIn',
            'Status' => 'required|in:Scheduled,OnLeave,Completed',
        ]);

        $workSchedule->update($validated);

        return redirect()
            ->route('receptionist.work-schedules.index')
            ->with('success', 'Đã cập nhật lịch làm việc.');
    }

    /**
     * Xoá lịch làm việc.
     */
    public function destroy(WorkSchedule $workSchedule)
    {
        $workSchedule->delete();

        return redirect()
            ->route('receptionist.work-schedules.index')
            ->with('success', 'Đã xoá lịch làm việc.');
    }
}
