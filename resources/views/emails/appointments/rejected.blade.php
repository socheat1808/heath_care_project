@component('mail::message')

<div style="text-align:center;margin-bottom:2rem">
    <h1 style="font-size:1.5rem;color:#1a8a6e;margin:0">One Health</h1>
    <p style="color:#6b7280;font-size:.875rem;margin:.25rem 0 0">Medical Appointment System</p>
</div>

# Appointment Update ❌

Hello, **{{ $patient->name }}**,

We're sorry to inform you that your appointment with Dr. {{ $doctor->first_name }} {{ $doctor->last_name }} has been **rejected**.

---

## Appointment Details

| Field | Details |
|-------|---------|
| **Doctor** | Dr. {{ $doctor->first_name }} {{ $doctor->last_name }} |
| **Date** | {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, d M Y') }} |
| **Time** | {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }} |
| **Department** | {{ $appointment->department ?? 'General' }} |

---

@if($rejection_reason)
## Reason for Rejection

@component('mail::panel')
{{ $rejection_reason }}
@endcomponent
@endif

You can book a new appointment with another available doctor from our platform.

@component('mail::button', ['url' => url('/find-doctors'), 'color' => 'success'])
Find Another Doctor
@endcomponent

We apologize for any inconvenience caused.

Thanks,<br>
**One Health Team**

@endcomponent