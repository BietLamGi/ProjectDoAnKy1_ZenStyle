<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $leaveRequests = LeaveRequest::with('user')
            ->orderByDesc('CreatedAt')
            ->paginate(10);

        return view(
            'admin.leave-requests.index',
            compact('leaveRequests')
        );
    }

    public function approve($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);

        if ($leaveRequest->Status !== 'Pending') {
            return back()->with(
                'error',
                'This leave request has already been processed.'
            );
        }

        $leaveRequest->update([
            'Status' => 'Approved',
            'UpdatedAt' => now(),
        ]);

        return back()->with(
            'success',
            'Leave request approved successfully.'
        );
    }

    public function reject(Request $request, $id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);

        if ($leaveRequest->Status !== 'Pending') {
            return back()->with(
                'error',
                'This leave request has already been processed.'
            );
        }

        $leaveRequest->update([
            'Status' => 'Rejected',
            'UpdatedAt' => now(),
        ]);

        return back()->with(
            'success',
            'Leave request rejected successfully.'
        );
    }
}