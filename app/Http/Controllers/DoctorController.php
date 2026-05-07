<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminDoctor;
use App\Models\User;
use App\Models\Appointments;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    // ── Admin: List All Doctors ──────────────────────────
    public function index()
    {
        $doctors = AdminDoctor::orderBy('first_name')->paginate(15);
        return view('admin.doctors.index', compact('doctors'));
    }

    // ── Admin: Create Doctor Form ────────────────────────
    public function create()
    {
        return view('admin.doctors.create');
    }

    // ── Admin: Store Doctor ──────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'first_name'          => 'required|string|max:100',
            'last_name'           => 'required|string|max:100',
            'email'               => 'required|email|unique:doctors,email|unique:users,email',
            'phone'               => 'nullable|string|max:20',
            'specialization'      => 'required|string',
            'status'              => 'required|in:available,unavailable,onleave',
            'years_of_experience' => 'nullable|integer|min:0|max:50',
            'consultation_fee'    => 'nullable|numeric|min:0',
            'biography_note'      => 'nullable|string',
            'photo'               => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'password'            => 'required|string|min:8',
        ]);

        // 1. Create doctors table record
        AdminDoctor::create([
            'first_name'          => $request->first_name,
            'last_name'           => $request->last_name,
            'email'               => $request->email,
            'phone'               => $request->phone,
            'specialization'      => $request->specialization,
            'status'              => $request->status,
            'years_of_experience' => $request->years_of_experience ?? 0,
            'consultation_fee'    => $request->consultation_fee ?? 0,
            'schedule_load'       => 0,
            'biography_note'      => $request->biography_note,
            'photo'               => $request->file('photo')
                ? $request->file('photo')->store('doctor-photos', 'public')
                : null,
        ]);

        // 2. Create users table record (for login)
        User::create([
            'name'              => $request->first_name . ' ' . $request->last_name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'role'              => 'doctor',
            'status'            => 'approved',  // admin-added = auto approved
            'phone'             => $request->phone,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.doctors.index')
            ->with('success', "Dr. {$request->first_name} {$request->last_name} added successfully.");
    }

    // ── Admin: Edit Doctor Form ──────────────────────────
    public function edit(string $id)
    {
        $doctor = AdminDoctor::findOrFail($id);
        return view('admin.doctors.edit', compact('doctor'));
    }

    // ── Admin: Update Doctor ─────────────────────────────
    public function update(Request $request, $id)
    {
        $doctor = AdminDoctor::findOrFail($id);

        $request->validate([
            'first_name'          => 'required|string|max:100',
            'last_name'           => 'required|string|max:100',
            'email'               => 'required|email|unique:doctors,email,' . $id . ',DoctorID',
            'specialization'      => 'required|string',
            'status'              => 'required|in:available,unavailable,onleave',
            'years_of_experience' => 'nullable|integer',
            'consultation_fee'    => 'nullable|numeric',
            'biography_note'      => 'nullable|string',
            'photo'               => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'password'            => 'nullable|string|min:8',
        ]);

        $data = $request->except(['_token', '_method', 'password', 'photo']);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('doctor-photos', 'public');
        }

        // 1. Update doctors table
        $doctor->update($data);

        // 2. Sync users table
        $user = User::where('email', $doctor->email)->first();
        if ($user) {
            $user->update([
                'name'  => $request->first_name . ' ' . $request->last_name,
                'phone' => $request->phone,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }
        }

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor updated successfully.');
    }

    // ── Admin: Delete Doctor ─────────────────────────────
    public function destroy(string $id)
    {
        $doctor = AdminDoctor::findOrFail($id);

        // Delete from both tables
        User::where('email', $doctor->email)->delete();
        $doctor->delete();

        return back()->with('success', 'Doctor removed successfully.');
    }

    // ── Doctor: Dashboard ────────────────────────────────
    public function dashboard()
    {
        $user   = Auth::user();
        $doctor = AdminDoctor::where('email', $user->email)->first();

        // Auto-create doctor record if missing (e.g. approved via DoctorApproval)
        if (!$doctor) {
            $parts  = explode(' ', trim($user->name));
            $doctor = AdminDoctor::create([
                'first_name'          => $parts[0],
                'last_name'           => implode(' ', array_slice($parts, 1)),
                'email'               => $user->email,
                'phone'               => $user->phone ?? null,
                'specialization'      => 'General Health',
                'status'              => 'available',
                'years_of_experience' => 0,
                'consultation_fee'    => 0,
                'schedule_load'       => 0,
            ]);
        }

        $doctorId = $doctor->DoctorID;

        $todayAppointments   = Appointments::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', today())
            ->where('status', 'approved')->count();

        $totalPatients       = Appointments::where('doctor_id', $doctorId)
            ->distinct('patient_id')->count();

        $pendingAppointments = Appointments::where('doctor_id', $doctorId)
            ->where('status', 'pending')->count();

        $completedThisMonth  = Appointments::where('doctor_id', $doctorId)
            ->where('status', 'completed')
            ->whereMonth('appointment_date', now()->month)->count();

        $todayList           = Appointments::with('patient')
            ->where('doctor_id', $doctorId)
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_time')->get();

        return view('doctor-dashboard.index', compact(
            'doctor',
            'todayAppointments',
            'totalPatients',
            'pendingAppointments',
            'completedThisMonth',
            'todayList'
        ));
    }

    // ── Doctor: View Profile Form ────────────────────────
    public function profile()
    {
        $user   = Auth::user();
        $doctor = AdminDoctor::where('email', $user->email)->first();
        return view('doctor-dashboard.doctor-profile.edit', compact('user', 'doctor'));
    }

    // ── Doctor: Update Profile → syncs admin doctors too ─
    public function updateProfile(Request $request)
    {
        $user   = User::find(Auth::id());
        $doctor = AdminDoctor::where('email', $user->email)->first();

        // Auto-create if missing
        if (!$doctor) {
            $parts  = explode(' ', trim($user->name));
            $doctor = AdminDoctor::create([
                'first_name'          => $parts[0],
                'last_name'           => implode(' ', array_slice($parts, 1)),
                'email'               => $user->email,
                'phone'               => $user->phone ?? null,
                'specialization'      => 'General Health',
                'status'              => 'available',
                'years_of_experience' => 0,
                'consultation_fee'    => 0,
                'schedule_load'       => 0,
            ]);
        }

        $request->validate([
            'first_name'          => 'required|string|max:100',
            'last_name'           => 'required|string|max:100',
            'phone'               => 'nullable|string|max:20',
            'specialization'      => 'required|string',
            'years_of_experience' => 'nullable|integer|min:0|max:50',
            'consultation_fee'    => 'nullable|numeric|min:0',
            'biography_note'      => 'nullable|string',
            'password'            => 'nullable|string|min:8|confirmed',
            'photo'               => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // 1. Update users table
        $user->update([
            'name'  => $request->first_name . ' ' . $request->last_name,
            'phone' => $request->phone,
        ]);

        // 2. Update doctors table (shows in admin panel immediately)
        $doctorData = [
            'first_name'          => $request->first_name,
            'last_name'           => $request->last_name,
            'phone'               => $request->phone,
            'specialization'      => $request->specialization,
            'biography_note'      => $request->biography_note,
            'years_of_experience' => $request->years_of_experience,
            'consultation_fee'    => $request->consultation_fee,
        ];

        if ($request->hasFile('photo')) {
            $doctorData['photo'] = $request->file('photo')->store('doctor-photos', 'public');
        }

        $doctor->update($doctorData);

        // 3. Update password in both tables if provided
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    // ── Doctor: Appointments ─────────────────────────────
    public function appointments()
    {
        $user   = Auth::user();
        $doctor = AdminDoctor::where('email', $user->email)->first();

        $appointments = Appointments::with('patient')
            ->where('doctor_id', optional($doctor)->DoctorID)
            ->orderBy('appointment_date', 'desc')
            ->paginate(15);

        return view('doctor-dashboard.appointments', compact('appointments', 'doctor'));
    }
}
