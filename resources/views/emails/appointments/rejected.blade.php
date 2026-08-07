@component('mail::message')

# Appointment Update ❌

Hello **{{ $patient->name }}**,

We're sorry to inform you that your appointment with **Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}** has been **rejected**.

@component('mail::panel')
**Appointment Details**
- **Doctor:** Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
- **Date:** {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, d M Y') }}
- **Time:** {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
- **Department:** {{ $appointment->department ?? 'General' }}
@endcomponent

@if($rejection_reason)
**Reason for Rejection:**
{{ $rejection_reason }}
@endif

@component('mail::button', ['url' => url('/find-doctors'), 'color' => 'success'])
Find Another Doctor
@endcomponent

We apologize for any inconvenience.

Thanks,
**One Health Team**

@endcomponent
