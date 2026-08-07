@component('mail::message')

# New Appointment Request 📅

Hello **Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}**,

You have a new appointment request. Please review and approve or reject it from your dashboard.

@component('mail::panel')
**Appointment Details**
- **Patient:** {{ $patient->name }}
- **Email:** {{ $patient->email }}
- **Date:** {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, d M Y') }}
- **Time:** {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
- **Department:** {{ $appointment->department ?? 'General' }}
- **Visit Type:** {{ ucfirst(str_replace('-', ' ', $appointment->visit_type ?? 'In Person')) }}
@if($appointment->notes)
- **Notes:** {{ $appointment->notes }}
@endif
@endcomponent

@component('mail::button', ['url' => url('/doctor-dashboard/appointments'), 'color' => 'success'])
Review Appointment
@endcomponent

Thanks,
**One Health Team**

@endcomponent
