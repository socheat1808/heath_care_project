@component('mail::message')

# New Doctor Registration 👨‍⚕️

A new doctor has registered and is waiting for your approval.

@component('mail::panel')
**Doctor Details**
- **Name:** {{ $user->name }}
- **Email:** {{ $user->email }}
- **Phone:** {{ $user->phone ?? '—' }}
- **Specialization:** {{ $specialization }}
- **Registered At:** {{ $user->created_at->format('d M Y, h:i A') }}
@endcomponent

@component('mail::button', ['url' => url('/admin/doctors/doctor-approvals'), 'color' => 'success'])
Review & Approve
@endcomponent

Please log in to your admin dashboard to approve or reject this doctor.

Thanks,
**One Health System**

@endcomponent
