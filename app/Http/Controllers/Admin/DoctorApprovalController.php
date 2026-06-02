<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Doctor;
use App\Http\Controllers\Controller;

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
        $user->update(['status' => 'approved']);

        $parts = explode(' ', trim($user->name));
        Doctor::updateOrCreate(
            ['email' => $user->email],
            [
                'first_name'          => $parts[0],
                'last_name'           => implode(' ', array_slice($parts, 1)),
                'email'               => $user->email,
                'phone'               => $user->phone ?? null,
                'specialization'      => 'General Health',
                'status'              => 'available',
                'years_of_experience' => 0,
                'consultation_fee'    => 0,
                'schedule_load'       => 0,
            ]
        );

        return back()->with('success', "Dr. {$user->name} has been approved.");
    }

    public function rejectDoctor(User $user)
    {
        $user->update(['status' => 'rejected']);
        Doctor::where('email', $user->email)->delete();
        return back()->with('success', "Dr. {$user->name} has been rejected.");
    }
}
