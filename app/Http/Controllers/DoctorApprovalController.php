<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdminDoctor;

class DoctorApprovalController extends Controller
{
    public function index()
    {
        $pending  = User::where('role', 'doctor')->where('status', 'pending')->latest()->get();
        $approved = User::where('role', 'doctor')->where('status', 'approved')->latest()->get();
        $rejected = User::where('role', 'doctor')->where('status', 'rejected')->latest()->get();

        return view('admin.doctors.doctor-approval', compact('pending', 'approved', 'rejected'));
    }

    public function approveDoctor(User $user)
    {
        // 1. Approve in users table
        $user->update(['status' => 'approved']);

        // 2. Create or update record in doctors table
        $parts = explode(' ', trim($user->name));

        AdminDoctor::updateOrCreate(
            ['email' => $user->email], // match by email
            [
                'first_name'          => $parts[0],
                'last_name'           => implode(' ', array_slice($parts, 1)),
                'email'               => $user->email,
                'phone'               => $user->phone ?? null,
                'specialization'      => 'General Health', // default — doctor can update in profile
                'status'              => 'available',      // ✅ now shows in admin doctors list
                'years_of_experience' => 0,
                'consultation_fee'    => 0,
                'schedule_load'       => 0,
            ]
        );

        return back()->with('success', "Dr. {$user->name} has been approved and added to doctors list.");
    }

    public function rejectDoctor(User $user)
    {
        // 1. Reject in users table
        $user->update(['status' => 'rejected']);

        // 2. Remove from doctors table if exists
        AdminDoctor::where('email', $user->email)->delete();

        return back()->with('success', "Dr. {$user->name} has been rejected.");
    }
}
