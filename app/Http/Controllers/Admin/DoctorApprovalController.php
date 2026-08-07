<?php

namespace App\Http\Controllers\Admin;

use App\Mail\DoctorApproved;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Doctor;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

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
                'phone'               => $user->phone,
                'specialization'      => 'General Health',
                'status'              => 'available',
                'years_of_experience' => 0,
                'consultation_fee'    => 0,
                'schedule_load'       => 0,
            ]
        );

        // ── Send approval email to doctor ──
        try {
            Mail::to($user->email)->send(new DoctorApproved($user));
        } catch (\Exception $e) {
            \Log::error('Doctor approval email failed: ' . $e->getMessage());
        }

        return back()->with('success', "Dr. {$user->name} has been approved and notified by email.");
    }

    public function rejectDoctor(User $user)
    {
        $user->update(['status' => 'rejected']);
        Doctor::where('email', $user->email)->delete();

        return back()->with('success', "Dr. {$user->name} has been rejected.");
    }
}
