@component('mail::message')

<div style="text-align:center;margin-bottom:2rem">
    <h1 style="font-size:1.5rem;color:#1a8a6e;margin:0">One Health</h1>
    <p style="color:#6b7280;font-size:.875rem;margin:.25rem 0 0">Medical Appointment System</p>
</div>

# New Appointment Request 📅

Hello, **Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}**,

You have a new appointment request from a patient. Please review and confirm or reject it from your dashboard.

---

## Appointment Details

| Field | Details |
|-------|---------|
| **Patient** | {{ $patient->name }} |
| **Date** | {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, d M Y') }} |
| **Time** | {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }} |
| **Department** | {{ $appointment->department ?? 'General' }} |
| **Visit Type** | {{ ucfirst(str_replace('-', ' ', $appointment->visit_type ?? 'In Person')) }} |
@if($appointment->reason)
| **Reason** | {{ $appointment->reason }} |
@endif

---

@component('mail::button', ['url' => url('/doctor-dashboard/appointments'), 'color' => 'success'])
Review Appointment
@endcomponent

Please log in to your dashboard to **approve** or **reject** this appointment.

Thanks,<br>
**One Health Team**

@endcomponent