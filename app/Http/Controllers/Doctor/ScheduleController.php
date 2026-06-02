<?php

namespace App\Http\Controllers\Doctor;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Doctor;

class ScheduleController extends Controller
{
    public function schedule()
    {
        $user   = Auth::user();
        $doctor = Doctor::where('email', $user->email)->first();
        $docId  = optional($doctor)->DoctorID;

        // All 7 schedule rows for this doctor (one per day)
        $schedules = \App\Models\DoctorSchedule::where('doctor_id', $docId)->get();

        // Stat: how many days are active
        $activeDays = $schedules->where('is_active', true)->count();

        // Stat: total weekly hours
        $totalHours = $schedules->where('is_active', true)->sum(function ($s) {
            if (!$s->start_time || !$s->end_time) return 0;
            $start = \Carbon\Carbon::parse($s->start_time);
            $end   = \Carbon\Carbon::parse($s->end_time);
            return max(0, $end->diffInHours($start));
        });

        // Stat: today's appointments
        $todayAppointments = \App\Models\Appointment::where('doctor_id', $docId)
            ->whereDate('appointment_date', today())
            ->where('status', 'approved')
            ->count();

        // This week's appointments grouped by date
        $weekAppointments = \App\Models\Appointment::with('patient')
            ->where('doctor_id', $docId)
            ->whereBetween('appointment_date', [
                now()->startOfWeek()->toDateString(),
                now()->endOfWeek()->toDateString(),
            ])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get()
            ->groupBy('appointment_date');

        return view('doctor-dashboard.doctor-schedule.index', compact(
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

        $request->validate([
            'doctor_status'                       => 'required|in:available,unavailable,onleave',
            'schedule.*.start_time'               => 'nullable|date_format:H:i',
            'schedule.*.end_time'                 => 'nullable|date_format:H:i|after:schedule.*.start_time',
            'schedule.*.max_appointments'         => 'nullable|integer|min:1|max:50',
        ]);

        // 1. Update doctor overall status
        $doctor->update(['status' => $request->doctor_status]);

        // 2. Upsert each day's schedule
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        foreach ($days as $day) {
            $data = $request->input("schedule.{$day}", []);

            \App\Models\DoctorSchedule::updateOrCreate(
                ['doctor_id' => $docId, 'day' => $day],
                [
                    'is_active'        => isset($data['is_active']) ? 1 : 0,
                    'start_time'       => $data['start_time']        ?? '09:00',
                    'end_time'         => $data['end_time']          ?? '17:00',
                    'max_appointments' => $data['max_appointments']  ?? 10,
                ]
            );
        }

        return back()->with('success', 'Schedule updated successfully.');
    }
}
