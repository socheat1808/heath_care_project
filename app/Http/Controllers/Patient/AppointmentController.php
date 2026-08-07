<?php

namespace App\Http\Controllers\Patient;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\DoctorSchedule;
use App\Http\Controllers\Controller;
use App\Mail\AppointmentBooked;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    // ── Patient: Dashboard ───────────────────────────────
    public function dashboard()
    {
        $patientId = Auth::id();
        $base      = Appointment::where('patient_id', $patientId);

        $totalAppointments = (clone $base)->count();
        $pendingCount      = (clone $base)->where('status', 'pending')->count();
        $upcomingCount     = (clone $base)->where('status', 'approved')
            ->where('appointment_date', '>=', today())->count();
        $completedCount    = (clone $base)->where('status', 'completed')->count();

        // Next 5 upcoming
        $upcomingList = Appointment::with('doctor')
            ->where('patient_id', $patientId)
            ->whereIn('status', ['approved', 'pending'])
            ->where('appointment_date', '>=', today())
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->take(5)
            ->get();

        // Last 5 for activity feed
        $recentActivity = Appointment::with('doctor')
            ->where('patient_id', $patientId)
            ->orderByDesc('updated_at')
            ->take(5)
            ->get();

        return view('patient.dashboard', compact(
            'totalAppointments',
            'pendingCount',
            'upcomingCount',
            'completedCount',
            'upcomingList',
            'recentActivity',
        ));
    }

    // ── Patient: Book Appointment Form ───────────────────
    public function create($doctorId)
    {
        $doctor    = Doctor::findOrFail($doctorId);
        $schedules = DoctorSchedule::where('doctor_id', $doctorId)->get();

        $todayName     = now()->format('l');
        $todaySchedule = $schedules->firstWhere('day', $todayName);

        $availableSlots = [];
        if ($todaySchedule && $todaySchedule->is_active) {
            $start  = \Carbon\Carbon::parse($todaySchedule->start_time);
            $end    = \Carbon\Carbon::parse($todaySchedule->end_time);
            $booked = Appointment::where('doctor_id', $doctorId)
                ->whereDate('appointment_date', today())
                ->whereIn('status', ['pending', 'approved'])
                ->pluck('appointment_time')
                ->map(fn($t) => \Carbon\Carbon::parse($t)->format('H:i'))
                ->toArray();

            while ($start < $end) {
                $slot = $start->format('H:i');
                if (!in_array($slot, $booked)) {
                    $availableSlots[] = $slot;
                }
                $start->addMinutes(30);
            }
        }

        return view('patient.appointments.book', compact('doctor', 'availableSlots', 'schedules'));
    }

    // ── Patient: Store Appointment ───────────────────────
    // ── Patient: Store Appointment ───────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id'        => 'required|exists:doctors,DoctorID',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'department'       => 'required|string|max:100',
            'visit_type'       => 'nullable|in:in-person,telemedicine,follow-up',
            'notes'            => 'nullable|string|max:2000',
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);

        if ($doctor->status !== 'available') {
            return back()->withErrors([
                'doctor' => 'This doctor is not currently available.',
            ]);
        }

        $dayName  = \Carbon\Carbon::parse($request->appointment_date)->format('l');
        $schedule = DoctorSchedule::where('doctor_id', $request->doctor_id)
            ->where('day', $dayName)
            ->where('is_active', true)
            ->first();

        if (!$schedule) {
            return back()->withErrors([
                'appointment_date' => 'The doctor does not work on that day. Please choose another date.',
            ]);
        }

        $bookedCount = Appointment::where('doctor_id', $request->doctor_id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->whereIn('status', ['pending', 'approved'])
            ->count();

        if ($bookedCount >= $schedule->max_appointments) {
            return back()->withErrors([
                'appointment_date' => 'This doctor is fully booked on that day. Please choose another date.',
            ]);
        }

        // ── Create appointment ──
        $appointment = Appointment::create([
            'doctor_id'        => $request->doctor_id,
            'patient_id'       => Auth::id(),
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'department'       => $request->department,
            'visit_type'       => $request->visit_type,
            'notes'            => $request->notes,
            'status'           => 'pending',
        ]);

        // ── Send email to doctor ──
        try {
            $appointment->load('patient', 'doctor');
            Mail::to($appointment->doctor->email)->send(new AppointmentBooked($appointment));
        } catch (\Exception $e) {
            \Log::error('Failed to send appointment booked email: ' . $e->getMessage());
        }

        return redirect()->route('patient.appointments.index')
            ->with('success', 'Appointment requested! You will be notified once approved.');
    }

    // ── Patient: My Appointments List ────────────────────
    public function index(Request $request)
    {
        $patientId = Auth::id();

        $query = Appointment::with('doctor')
            ->where('patient_id', $patientId)
            ->orderByDesc('appointment_date')
            ->orderByDesc('appointment_time');

        if ($request->filled('status')) {
            if ($request->status === 'cancelled') {
                $query->whereIn('status', ['cancelled', 'rejected']);
            } else {
                $query->where('status', $request->status);
            }
        }

        $appointments    = $query->paginate(8)->withQueryString();
        $allAppointments = Appointment::where('patient_id', $patientId)->get();

        return view('patient.appointments.my-appointments', compact('appointments', 'allAppointments'));
    }

    // ── Patient: Cancel Appointment ──────────────────────
    public function cancel(Appointment $appointment)
    {
        abort_if($appointment->patient_id !== Auth::id(), 403);

        if ($appointment->status !== 'pending') {
            return back()->withErrors([
                'cancel' => 'Only pending appointments can be cancelled.',
            ]);
        }

        $appointment->update(['status' => 'cancelled']);

        return back()->with('success', 'Appointment cancelled successfully.');
    }
    public function doctorProfile($doctorId)
    {
        $doctor = Doctor::findOrFail($doctorId);
        $schedules = $doctor->schedules;
        return view('patient.appointments.doctor-profile', compact('doctor', 'schedules'));
    }
}
