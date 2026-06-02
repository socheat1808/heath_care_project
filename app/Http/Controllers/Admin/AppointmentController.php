<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\User;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Appointment::with(['patient', 'doctor']);

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }
        if ($request->filled('patient')) {
            $query->whereHas('patient', fn($q) => $q->where('name', 'like', '%' . $request->patient . '%'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'asc')
            ->paginate(15)
            ->withQueryString();

        $doctors = Doctor::select('DoctorID', 'first_name', 'last_name')->get();

        $stats = [
            'approved'  => Appointment::where('status', 'approved')->whereDate('appointment_date', today())->count(),
            'pending'   => Appointment::where('status', 'pending')->count(),
            'month'     => Appointment::whereMonth('appointment_date', now()->month)->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->whereDate('appointment_date', today())->count(),
        ];

        $calendarData = Appointment::with(['patient', 'doctor'])
            ->whereBetween('appointment_date', [now(), now()->addDays(30)])
            ->whereNotIn('status', ['rejected', 'cancelled'])
            ->get()
            ->groupBy(fn($a) => $a->appointment_date->format('Y-m-d'));

        return view('admin.appointments.index', compact(
            'appointments',
            'doctors',
            'stats',
            'calendarData'
        ));
    }
    public function create(): View
    {
        $doctors  = Doctor::select('DoctorID', 'first_name', 'last_name', 'specialization', 'consultation_fee')
            ->where('status', 'available')
            ->get();

        $patients = User::select('id', 'name', 'email')
            ->where('role', 'patient')
            ->orderBy('name')
            ->get();

        return view('admin.appointments.create', compact('doctors', 'patients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'PatientID'        => ['required', 'exists:users,id'],
            'DoctorID'         => ['required', 'exists:doctors,DoctorID'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_time' => ['required'],
            'status'           => ['required', 'in:pending,approved,completed,cancelled'],
            'reason'           => ['nullable', 'string', 'max:255'],
            'notes'            => ['nullable', 'string', 'max:1000'],
        ]);

        Appointment::create([
            'patient_id'       => $request->PatientID,   // ✅
            'doctor_id'        => $request->DoctorID,     // ✅
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'status'           => $request->status,
            'reason'           => $request->reason,
            'notes'            => $request->notes,
        ]);

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment created successfully.');
    }


    public function approve(Appointment $appointment): RedirectResponse
    {
        $appointment->update(['status' => 'approved']);
        return back()->with('success', 'Appointment approved successfully.');
    }

    public function reject(Request $request, Appointment $appointment): RedirectResponse
    {
        $request->validate(['rejection_reason' => ['required', 'string', 'max:500']]);
        $appointment->update(['status' => 'rejected', 'rejection_reason' => $request->rejection_reason]);
        return back()->with('success', 'Appointment rejected.');
    }

    public function complete(Appointment $appointment): RedirectResponse
    {
        $appointment->update(['status' => 'completed']);
        return back()->with('success', 'Appointment marked as completed.');
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        $appointment->delete();
        return back()->with('success', 'Appointment deleted.');
    }
    //  Dashboard with stats, today's appointments, doctor availability, and recent activity

    public function dashboard(): View
    {
        // Stat cards
        $totalPatients      = User::where('role', 'patient')->count();
        $totalDoctors       = Doctor::where('status', 'available')->count();
        $monthAppointments  = Appointment::whereMonth('appointment_date', now()->month)->count();
        $pendingApprovals   = User::where('role', 'doctor')->where('status', 'pending')->count();

        // Today's appointments
        $todayAppointments  = Appointment::with(['patient', 'doctor'])
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_time')
            ->get();

        // Doctor availability
        $doctors = Doctor::where('status', 'available')
            ->withCount(['appointments as today_count' => function ($q) {
                $q->whereDate('appointment_date', today());
            }])
            ->limit(5)
            ->get();

        // Recent appointments
        $recentAppointments = Appointment::with(['patient', 'doctor'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPatients',
            'totalDoctors',
            'monthAppointments',
            'pendingApprovals',
            'todayAppointments',
            'doctors',
            'recentAppointments',
        ));
    }
}
