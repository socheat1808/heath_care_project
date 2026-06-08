<?php

namespace App\Http\Controllers\Admin;

use App\Models\LeaveRequest;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LeaveController extends Controller
{
    public function index()
    {
        $leaveRequests = LeaveRequest::with('doctor')
            ->orderByDesc('created_at')
            ->paginate(15);

        $pendingCount = LeaveRequest::where('status', 'pending')->count();

        return view('admin.leave.index', compact('leaveRequests', 'pendingCount'));
    }

    public function approve(LeaveRequest $leaveRequest)
    {
        $leaveRequest->update(['status' => 'approved']);

        // Update doctor status to onleave
        Doctor::where('DoctorID', $leaveRequest->doctor_id)
            ->update(['status' => 'onleave']);

        return back()->with('success', 'Leave request approved. Doctor status set to On Leave.');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $leaveRequest->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Leave request rejected.');
    }
}
