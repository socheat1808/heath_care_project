<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AdminDoctor;
use App\Models\Appointments;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\User;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Appointments::with(['patient', 'doctor']);

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

        $doctors = AdminDoctor::select('DoctorID', 'first_name', 'last_name')->get();

        $stats = [
            'approved'  => Appointments::where('status', 'approved')->whereDate('appointment_date', today())->count(),
            'pending'   => Appointments::where('status', 'pending')->count(),
            'month'     => Appointments::whereMonth('appointment_date', now()->month)->count(),
            'cancelled' => Appointments::where('status', 'cancelled')->whereDate('appointment_date', today())->count(),
        ];

        $calendarData = Appointments::with(['patient', 'doctor'])
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
        $doctors  = AdminDoctor::select('DoctorID', 'first_name', 'last_name', 'specialization', 'consultation_fee')
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

        Appointments::create([
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


    public function approve(Appointments $appointment): RedirectResponse
    {
        $appointment->update(['status' => 'approved']);
        return back()->with('success', 'Appointment approved successfully.');
    }

    public function reject(Request $request, Appointments $appointment): RedirectResponse
    {
        $request->validate(['rejection_reason' => ['required', 'string', 'max:500']]);
        $appointment->update(['status' => 'rejected', 'rejection_reason' => $request->rejection_reason]);
        return back()->with('success', 'Appointment rejected.');
    }

    public function complete(Appointments $appointment): RedirectResponse
    {
        $appointment->update(['status' => 'completed']);
        return back()->with('success', 'Appointment marked as completed.');
    }

    public function destroy(Appointments $appointment): RedirectResponse
    {
        $appointment->delete();
        return back()->with('success', 'Appointment deleted.');
    }
}
