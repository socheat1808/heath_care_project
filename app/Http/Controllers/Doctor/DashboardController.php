<?php

namespace App\Http\Controllers\Doctor;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\DoctorSchedule;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    // ── Admin: List All Doctors ──────────────────────────
    public function index()
    {
        $doctors = Doctor::orderBy('first_name')->paginate(15);
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
        Doctor::create([
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
        $doctor = Doctor::findOrFail($id);
        return view('admin.doctors.edit', compact('doctor'));
    }

    // ── Admin: Update Doctor ─────────────────────────────
    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

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
        $doctor = Doctor::findOrFail($id);

        // Delete from both tables
        User::where('email', $doctor->email)->delete();
        $doctor->delete();

        return back()->with('success', 'Doctor removed successfully.');
    }

    // ── Doctor: Dashboard ────────────────────────────────
    public function dashboard()
    {
        $user   = Auth::user();
        $doctor = Doctor::where('email', $user->email)->first();

        // Auto-create if missing
        if (!$doctor) {
            $parts  = explode(' ', trim($user->name));
            $doctor = Doctor::create([
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

        $todayAppointments   = \App\Models\Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', today())
            ->where('status', 'approved')->count();

        $totalPatients       = \App\Models\Appointment::where('doctor_id', $doctorId)
            ->distinct('patient_id')->count();

        $pendingAppointments = \App\Models\Appointment::where('doctor_id', $doctorId)
            ->where('status', 'pending')->count();

        $completedThisMonth  = \App\Models\Appointment::where('doctor_id', $doctorId)
            ->where('status', 'completed')
            ->whereMonth('appointment_date', now()->month)->count();

        $todayList           = \App\Models\Appointment::with('patient')
            ->where('doctor_id', $doctorId)
            ->whereDate('appointment_date', today())
            ->where('status', 'approved')
            ->orderBy('appointment_time')
            ->get();

        return view('doctor.dashboard', compact(
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
        $doctor = Doctor::where('email', $user->email)->first();
        return view('doctor.profile.edit', compact('user', 'doctor'));
    }

    // ── Doctor: Update Profile → syncs admin doctors too ─
    public function updateProfile(Request $request)
    {
        $user   = User::find(Auth::id());
        $doctor = Doctor::where('email', $user->email)->first();

        // Auto-create if missing
        if (!$doctor) {
            $parts  = explode(' ', trim($user->name));
            $doctor = Doctor::create([
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
    // ── Doctor: Appointments ─────────────────────────────
    public function appointments(Request $request)
    {
        $user   = Auth::user();
        $doctor = Doctor::where('email', $user->email)->first();
        $docId  = optional($doctor)->DoctorID;

        // ── Base query ───────────────────────────────────────
        $query = \App\Models\Appointment::with('patient')
            ->where('doctor_id', $docId)
            ->orderBy('appointment_date')
            ->orderBy('appointment_time');

        // ── Tab filter ───────────────────────────────────────
        $tab = $request->get('tab', 'all');

        if ($tab === 'today') {
            $query->whereDate('appointment_date', today())
                ->where('status', 'approved');
        } elseif ($tab === 'upcoming') {
            $query->where('appointment_date', '>=', today())
                ->where('status', 'approved');
        } elseif ($tab === 'completed') {
            $query->where('status', 'completed');
        }

        // ── Search by patient name / email ───────────────────
        if ($request->filled('search')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // ── Status filter ────────────────────────────────────
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // ── Date filter ──────────────────────────────────────
        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        $appointments = $query->paginate(15)->withQueryString();

        // ── Stat counts ──────────────────────────────────────
        $base = \App\Models\Appointment::where('doctor_id', $docId);

        $stats = [
            'today'     => (clone $base)->whereDate('appointment_date', today())->where('status', 'approved')->count(),
            'pending'   => (clone $base)->where('status', 'pending')->count(),
            'upcoming'  => (clone $base)->where('appointment_date', '>=', today())->where('status', 'approved')->count(),
            'completed' => (clone $base)->where('status', 'completed')->count(),
            'cancelled' => (clone $base)->where('status', 'cancelled')
                ->whereMonth('appointment_date', now()->month)
                ->whereYear('appointment_date', now()->year)->count(),
        ];

        return view('doctor.appointments.index', [
            'appointments'   => $appointments,
            'todayCount'     => $stats['today'],
            'pendingCount'   => $stats['pending'],
            'completedCount' => $stats['completed'],
            'cancelledCount' => $stats['cancelled'],   // ← real cancelled count now
        ]);
    }
    // ── Doctor: Create Appointment Form ─────────────────
    public function createAppointment()
    {
        $user     = Auth::user();
        $doctor   = Doctor::where('email', $user->email)->first();

        // Only show patients (role = 'patient')
        $patients = User::where('role', 'patient')
            ->orderBy('name')
            ->get();

        return view('doctor.appointments.create', compact('doctor', 'patients'));
    }

    // ── Doctor: Store Appointment ────────────────────────
    public function storeAppointment(Request $request)
    {
        $user   = Auth::user();
        $doctor = Doctor::where('email', $user->email)->first();

        $request->validate([
            'patient_id'       => 'required|exists:users,id',
            'department'       => 'required|string|max:100',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'status'           => 'required|in:pending,approved,completed,cancelled',
            'visit_type'       => 'nullable|in:in-person,telemedicine,follow-up,emergency',
            'notes'            => 'nullable|string|max:2000',
        ]);

        Appointment::create([
            'doctor_id'        => $doctor->DoctorID,
            'patient_id'       => $request->patient_id,
            'department'       => $request->department,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'status'           => $request->status,
            'visit_type'       => $request->visit_type,
            'notes'            => $request->notes,
        ]);

        return redirect()->route('doctor.appointments.index')
            ->with('success', 'Appointment created successfully.');
    }

    // doctor: myPatients
    public function myPatients()
    {
        $doctor = Doctor::where('email', Auth::user()->email)->firstOrFail();

        $patients = Appointment::with('patient')
            ->where('doctor_id', $doctor->DoctorID)
            ->whereNotNull('patient_id')
            ->get()
            ->groupBy('patient_id')
            ->map(function ($appointments) {
                return [
                    'patient'      => $appointments->first()->patient,
                    'total'        => $appointments->count(),
                    'last_visit'   => $appointments->sortByDesc('appointment_date')->first()->appointment_date,
                    'last_status'  => $appointments->sortByDesc('appointment_date')->first()->status,
                    'completed'    => $appointments->where('status', 'completed')->count(),
                    'pending'      => $appointments->where('status', 'pending')->count(),
                ];
            })
            ->values();

        $totalPatients   = $patients->count();
        $totalCompleted  = Appointment::where('doctor_id', $doctor->DoctorID)->where('status', 'completed')->count();
        $totalPending    = Appointment::where('doctor_id', $doctor->DoctorID)->where('status', 'pending')->count();
        $totalToday      = Appointment::where('doctor_id', $doctor->DoctorID)->whereDate('appointment_date', today())->count();

        return view('doctor.patients.index', compact(
            'patients',
            'totalPatients',
            'totalCompleted',
            'totalPending',
            'totalToday'
        ));
    }
    // ── Doctor: Schedule View ────────────────────────────────────
    public function schedule()
    {
        $user   = Auth::user();
        $doctor = Doctor::where('email', $user->email)->first();
        $docId  = optional($doctor)->DoctorID;

        $schedules = \App\Models\DoctorSchedule::where('doctor_id', $docId)->get();

        $activeDays = $schedules->where('is_active', true)->count();

        $totalHours = $schedules->where('is_active', true)->sum(function ($s) {
            if (!$s->start_time || !$s->end_time) return 0;
            $start = \Carbon\Carbon::parse($s->start_time);
            $end   = \Carbon\Carbon::parse($s->end_time);
            return max(0, $end->diffInHours($start));
        });

        $todayAppointments = Appointment::where('doctor_id', $docId)
            ->whereDate('appointment_date', today())
            ->where('status', 'approved')
            ->count();

        $weekAppointments = Appointment::with('patient')
            ->where('doctor_id', $docId)
            ->whereBetween('appointment_date', [
                now()->startOfWeek()->toDateString(),
                now()->endOfWeek()->toDateString(),
            ])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get()
            ->groupBy('appointment_date');

        return view('doctor.schedule.index', compact(
            'doctor',
            'schedules',
            'activeDays',
            'totalHours',
            'todayAppointments',
            'weekAppointments',
        ));
    }

    // ── Doctor: Update Schedule ──────────────────────────────────
    public function updateSchedule(Request $request)
    {
        $user   = Auth::user();
        $doctor = Doctor::where('email', $user->email)->first();
        $docId  = optional($doctor)->DoctorID;

        // ── Convert times to H:i before validation ──
        $schedule = $request->input('schedule', []);
        foreach ($schedule as $day => $data) {
            if (!empty($data['start_time'])) {
                try {
                    $schedule[$day]['start_time'] = \Carbon\Carbon::parse($data['start_time'])->format('H:i');
                } catch (\Exception $e) {
                    $schedule[$day]['start_time'] = '09:00';
                }
            }
            if (!empty($data['end_time'])) {
                try {
                    $schedule[$day]['end_time'] = \Carbon\Carbon::parse($data['end_time'])->format('H:i');
                } catch (\Exception $e) {
                    $schedule[$day]['end_time'] = '17:00';
                }
            }
        }
        $request->merge(['schedule' => $schedule]);

        // ── Validate ──
        $request->validate([
            'doctor_status'               => 'required|in:available,unavailable,onleave',
            'schedule.*.start_time'       => 'nullable|date_format:H:i',
            'schedule.*.end_time'         => 'nullable|date_format:H:i',
            'schedule.*.max_appointments' => 'nullable|integer|min:1|max:50',
        ]);

        $doctor->update(['status' => $request->doctor_status]);

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        foreach ($days as $day) {
            $data = $request->input("schedule.{$day}", []);

            \App\Models\DoctorSchedule::updateOrCreate(
                ['doctor_id' => $docId, 'day' => $day],
                [
                    'is_active'        => isset($data['is_active']) ? 1 : 0,
                    'start_time'       => $data['start_time']       ?? '09:00',
                    'end_time'         => $data['end_time']         ?? '17:00',
                    'max_appointments' => $data['max_appointments'] ?? 10,
                ]
            );
        }

        return back()->with('success', 'Schedule updated successfully.');
    }


    public function completeAppointment(Appointment $appointment)
    {
        $doctor = Doctor::where('email', Auth::user()->email)->first();
        abort_if($appointment->doctor_id !== $doctor->DoctorID, 403);
        $appointment->update(['status' => 'completed']);
        return back()->with('success', 'Appointment marked as completed.');
    }

    public function addNotes(Request $request, Appointment $appointment)
    {
        $doctor = Doctor::where('email', Auth::user()->email)->first();
        abort_if($appointment->doctor_id !== $doctor->DoctorID, 403);
        $request->validate(['notes' => 'required|string|max:2000']);
        $appointment->update(['notes' => $request->notes]);
        return back()->with('success', 'Notes saved.');
    }

    // ── Doctor: Approve Appointment ──────────────────────
    public function approveAppointment(Appointment $appointment)
    {
        $doctor = Doctor::where('email', Auth::user()->email)->first();
        abort_if($appointment->doctor_id !== $doctor->DoctorID, 403);

        $appointment->update(['status' => 'approved']);
        return back()->with('success', 'Appointment approved successfully.');
    }

    // ── Doctor: Reject Appointment ───────────────────────
    public function rejectAppointment(Request $request, Appointment $appointment)
    {
        $doctor = Doctor::where('email', Auth::user()->email)->first();
        abort_if($appointment->doctor_id !== $doctor->DoctorID, 403);

        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $appointment->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Appointment rejected.');
    }

    // ── Doctor: Cancel Appointment ───────────────────────
    public function cancelAppointment(Appointment $appointment)
    {
        $doctor = Doctor::where('email', Auth::user()->email)->first();
        abort_if($appointment->doctor_id !== $doctor->DoctorID, 403);

        $appointment->update(['status' => 'cancelled']);
        return back()->with('success', 'Appointment cancelled.');
    }
    // ── Doctor: Patient Notes History ────────────────────
    public function patientNotes($patientId)
    {
        $doctor = Doctor::where('email', Auth::user()->email)->firstOrFail();

        $patient = User::findOrFail($patientId);

        $appointments = Appointment::where('doctor_id', $doctor->DoctorID)
            ->where('patient_id', $patientId)
            ->orderByDesc('appointment_date')
            ->get();

        return view('doctor.patients.notes', compact('patient', 'appointments', 'doctor'));
    }
}
