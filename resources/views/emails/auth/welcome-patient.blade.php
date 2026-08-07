@component('mail::message')

# Welcome to One Health! 🏥

Hello **{{ $user->name }}**,

Thank you for joining One Health. Your account has been created successfully.

You can now browse our qualified doctors and book appointments anytime.

@component('mail::panel')
**Your Account Details**
- **Name:** {{ $user->name }}
- **Email:** {{ $user->email }}
- **Role:** Patient
@endcomponent

@component('mail::button', ['url' => url('/patient-dashboard'), 'color' => 'success'])
Go to My Dashboard
@endcomponent

**What you can do:**
- 🔍 Browse available doctors
- 📅 Book appointments online
- 📋 Track your appointment history
- 💊 View doctor notes and prescriptions

Thanks,
**One Health Team**

@endcomponent
