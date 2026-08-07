@component('mail::message')

# Appointment Confirmed ✅

Hello **{{ $patient->name }}**,

Your appointment has been **confirmed** by Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}.

@component('mail::panel')
**Appointment Details**
- **Doctor:** Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
- **Specialization:** {{ $doctor->specialization ?? 'General Health' }}
- **Date:** {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, d M Y') }}
- **Time:** {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
- **Department:** {{ $appointment->department ?? 'General' }}
- **Visit Type:** {{ ucfirst(str_replace('-', ' ', $appointment->visit_type ?? 'In Person')) }}
@endcomponent

> **Reminder:** Please arrive 10 minutes early. If you need to cancel, please do so at least 24 hours in advance.

@component('mail::button', ['url' => url('/patient/appointments'), 'color' => 'success'])
View My Appointments
@endcomponent

We look forward to seeing you!

Thanks,
**One Health Team**

@endcomponent
