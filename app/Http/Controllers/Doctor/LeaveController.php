<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    // Show leave requests + form
    // FROM (shows ALL leave requests)
    public function index()
    {
        $doctor = Doctor::where('email', Auth::user()->email)->firstOrFail();

        $leaveRequests = LeaveRequest::where('doctor_id', $doctor->DoctorID)
            ->orderByDesc('created_at')
            ->get();

        return view('doctor.leave.index', compact('doctor', 'leaveRequests'));
    }

    // Store new leave request
    public function store(Request $request)
    {
        $doctor = Doctor::where('email', Auth::user()->email)->firstOrFail();

        $request->validate([
            'from_date' => 'required|date|after_or_equal:today',
            'to_date'   => 'required|date|after_or_equal:from_date',
            'reason'    => 'required|string|max:500',
        ]);

        // Check no pending request exists
        $existing = LeaveRequest::where('doctor_id', $doctor->DoctorID)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->with('error', 'You already have a pending leave request.');
        }

        LeaveRequest::create([
            'doctor_id' => $doctor->DoctorID,
            'from_date' => $request->from_date,
            'to_date'   => $request->to_date,
            'reason'    => $request->reason,
            'status'    => 'pending',
        ]);

        return back()->with('success', 'Leave request submitted successfully.');
    }

    // Cancel own leave request
    public function cancel(LeaveRequest $leaveRequest)
    {
        $doctor = Doctor::where('email', Auth::user()->email)->firstOrFail();
        abort_if($leaveRequest->doctor_id !== $doctor->DoctorID, 403);
        abort_if($leaveRequest->status !== 'pending', 403);

        $leaveRequest->delete();
        return back()->with('success', 'Leave request cancelled.');
    }
}
