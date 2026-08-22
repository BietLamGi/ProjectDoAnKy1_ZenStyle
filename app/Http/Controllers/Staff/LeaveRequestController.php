<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $leaveRequests = LeaveRequest::where('UserID', $user->UserID)
            ->orderByDesc('CreatedAt')
            ->get();

        return view(
            'staff.leave-requests.index',
            compact('leaveRequests')
        );
    }

    public function create()
    {
        return view('staff.leave-requests.create');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'LeaveStartDate' => [
            'required',
            'date',
            'after_or_equal:today',
        ],

        'LeaveEndDate' => [
            'required',
            'date',
            'after_or_equal:LeaveStartDate',
        ],

        'Reason' => [
            'nullable',
            'string',
            'max:500',
        ],
    ], [
        'LeaveStartDate.required' =>
            'Please select the start date.',

        'LeaveStartDate.after_or_equal' =>
            'The start date cannot be in the past.',

        'LeaveEndDate.required' =>
            'Please select the end date.',

        'LeaveEndDate.after_or_equal' =>
            'The end date must be after or equal to the start date.',

        'Reason.max' =>
            'The reason may not exceed 500 characters.',
    ]);

        $user = Auth::user();

        $exists = LeaveRequest::where('UserID', $user->UserID)
            ->whereIn('Status', ['Pending', 'Approved'])
            ->whereDate(
                'LeaveStartDate',
                '<=',
                $validated['LeaveEndDate']
            )
            ->whereDate(
                'LeaveEndDate',
                '>=',
                $validated['LeaveStartDate']
            )
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'LeaveStartDate' =>
                        'You already have a leave request for this period.'
                ]);
        }

        LeaveRequest::create([
            'UserID' => $user->UserID,
            'LeaveStartDate' => $validated['LeaveStartDate'],
            'LeaveEndDate' => $validated['LeaveEndDate'],
            'Reason' => $validated['Reason'] ?? null,
            'Status' => 'Pending',
            'CreatedAt' => now(),
        ]);

        return redirect()
            ->route('staff.leave-requests.index')
            ->with('success', 'Leave request submitted successfully.');
    }

    public function destroy($id)
    {
        $user = Auth::user();

        $leaveRequest = LeaveRequest::where('LeaveRequestID', $id)
            ->where('UserID', $user->UserID)
            ->firstOrFail();

        if ($leaveRequest->Status !== 'Pending') {
            return back()->with(
                'error',
                'Only pending requests can be cancelled.'
            );
        }

        $leaveRequest->update([
            'Status' => 'Cancelled',
            'UpdatedAt' => now(),
        ]);

        return back()->with(
            'success',
            'Leave request cancelled successfully.'
        );
    }
}