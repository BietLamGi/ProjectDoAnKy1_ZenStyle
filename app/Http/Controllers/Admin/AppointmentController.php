<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['customer', 'staff'])
            ->orderBy('AppointmentDate', 'desc')
            ->orderBy('StartTime', 'desc')
            ->paginate(10);

        return view('admin.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $customers = Customer::orderBy('FullName')->get();

        $staff = User::where('RoleID', 2)
            ->where('IsActive', 1)
            ->orderBy('Username')
            ->get();

        return view('admin.appointments.create', compact(
            'customers',
            'staff'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'CustomerID' => 'required|integer|exists:Customer,CustomerID',
            'StaffID' => 'nullable|integer|exists:User,UserID',
            'AppointmentDate' => 'required|date',
            'StartTime' => 'required',
            'EndTime' => 'required|after:StartTime',
            'Status' => 'required|string|max:20',
            'Notes' => 'nullable|string|max:500',
        ]);

        Appointment::create($validated);

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Thêm lịch hẹn thành công!');
    }

    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);

        $customers = Customer::orderBy('FullName')->get();

        $staff = User::where('RoleID', 2)
            ->where('IsActive', 1)
            ->orderBy('Username')
            ->get();

        return view('admin.appointments.edit', compact(
            'appointment',
            'customers',
            'staff'
        ));
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $validated = $request->validate([
            'CustomerID' => 'required|integer|exists:Customer,CustomerID',
            'StaffID' => 'nullable|integer|exists:User,UserID',
            'AppointmentDate' => 'required|date',
            'StartTime' => 'required',
            'EndTime' => 'required|after:StartTime',
            'Status' => 'required|string|max:20',
            'Notes' => 'nullable|string|max:500',
        ]);

        $appointment->update($validated);

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Cập nhật lịch hẹn thành công!');
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);

        $appointment->delete();

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Xóa lịch hẹn thành công!');
    }
}