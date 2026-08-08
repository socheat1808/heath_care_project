@component('mail::message')

# Appointment Reminder ⏰

Hello **{{ $patient->name }}**,

This is a friendly reminder that you have an appointment **tomorrow**.

@component('mail::panel')
**Appointment Details**
- **Doctor:** Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
- **Specialization:** {{ $doctor->specialization ?? 'General Health' }}
- **Date:** {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, d M Y') }}
- **Time:** {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
- **Department:** {{ $appointment->department ?? 'General' }}
- **Visit Type:** {{ ucfirst(str_replace('-', ' ', $appointment->visit_type ?? 'In Person')) }}
@endcomponent

> **Please arrive 10 minutes early** and bring any relevant medical documents or test results.

@component('mail::button', ['url' => url('/patient/appointments'), 'color' => 'success'])
View My Appointments
@endcomponent

If you need to cancel, please do so as soon as possible from your dashboard.

Thanks,
**One Health Team**

@endcomponent
