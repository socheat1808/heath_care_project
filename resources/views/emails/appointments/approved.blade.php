@component('mail::message')

<div style="text-align:center;margin-bottom:2rem">
    <h1 style="font-size:1.5rem;color:#1a8a6e;margin:0">One Health</h1>
    <p style="color:#6b7280;font-size:.875rem;margin:.25rem 0 0">Medical Appointment System</p>
</div>

# Appointment Confirmed ✅

Hello, **{{ $patient->name }}**,

Great news! Your appointment has been **confirmed** by Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}.

---

## Appointment Details

| Field | Details |
|-------|---------|
| **Doctor** | Dr. {{ $doctor->first_name }} {{ $doctor->last_name }} |
| **Specialization** | {{ $doctor->specialization ?? 'General Health' }} |
| **Date** | {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, d M Y') }} |
| **Time** | {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }} |
| **Department** | {{ $appointment->department ?? 'General' }} |
| **Visit Type** | {{ ucfirst(str_replace('-', ' ', $appointment->visit_type ?? 'In Person')) }} |

---

@component('mail::panel')
**Important:** Please arrive 10 minutes before your scheduled appointment time. If you need to cancel, please do so from your dashboard at least 24 hours in advance.
@endcomponent

@component('mail::button', ['url' => url('/patient/appointments'), 'color' => 'success'])
View My Appointments
@endcomponent

We look forward to seeing you soon!

Thanks,<br>
**One Health Team**

@endcomponent