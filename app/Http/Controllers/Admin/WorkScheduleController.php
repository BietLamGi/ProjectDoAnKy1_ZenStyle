<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkSchedule;
use Illuminate\Http\Request;

class WorkScheduleController extends Controller
{
    public function index()
    {
        $schedules = WorkSchedule::with('user')
            ->orderBy('WorkDate', 'desc')
            ->orderBy('ShiftStart')
            ->paginate(10);

        return view('admin.work-schedules.index', compact('schedules'));
    }

    public function create()
{
    $users = User::where('IsActive', 1)
        ->where('RoleID', 3)
        ->orderBy('Username')
        ->get();

    return view('admin.work-schedules.create', compact('users'));
}

  public function store(Request $request)
{
    $data = $request->validate([
        'UserID' => 'required|exists:User,UserID',
        'WorkDate' => 'required|date',
        'ShiftStart' => 'required|date_format:H:i',
        'ShiftEnd' => 'required|date_format:H:i|after:ShiftStart',

        'ActualCheckIn' => 'nullable|date',
        'ActualCheckOut' => 'nullable|date|after:ActualCheckIn',

        'Status' => 'required|in:Scheduled,OnLeave,Completed',
    ]);

    // WorkedHours là computed column bên SQL Server (tự tính từ
    // ActualCheckIn/ActualCheckOut) - không được phép gán tay, SQL Server
    // sẽ từ chối UPDATE/INSERT nếu cố set giá trị cho cột này.
    WorkSchedule::create($data);

    return redirect()
        ->route('work-schedules.index')
        ->with('success', 'Work schedule created successfully.');
}

    public function edit(WorkSchedule $workSchedule)
    {
        $users = User::where('IsActive', 1)
            ->where('RoleID', 2)
            ->orderBy('Username')
            ->get();

        return view(
            'admin.work-schedules.edit',
            compact('workSchedule', 'users')
        );
    }

    public function update(Request $request, WorkSchedule $workSchedule)
    {
        $validated = $request->validate([
            'UserID' => 'required|exists:User,UserID',
            'WorkDate' => 'required|date',
           'ShiftStart' => 'required|date_format:H:i',
'ShiftEnd' => 'required|date_format:H:i|after:ShiftStart',
            'ActualCheckIn' => 'nullable|date',
'ActualCheckOut' => 'nullable|date|after:ActualCheckIn',
            'Status' => 'required|in:Scheduled,OnLeave,Completed',
        ]);

        // WorkedHours là computed column - không set tay (xem ghi chú ở store()).
        $workSchedule->update($validated);

        return redirect()
            ->route('work-schedules.index')
            ->with('success', 'Work schedule updated successfully.');
    }

    public function destroy(WorkSchedule $workSchedule)
    {
        $workSchedule->delete();

        return redirect()
            ->route('work-schedules.index')
            ->with('success', 'Work schedule deleted successfully.');
    }
}