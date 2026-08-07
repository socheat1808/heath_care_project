@component('mail::message')

# Account Approved! ✅

Hello **{{ $user->name }}**,

Great news! Your doctor account on One Health has been **approved**. You can now log in and start managing your appointments.

@component('mail::panel')
**Your Account Details**
- **Name:** {{ $user->name }}
- **Email:** {{ $user->email }}
- **Role:** Doctor
- **Status:** Approved ✅
@endcomponent

@component('mail::button', ['url' => url('/doctor-dashboard'), 'color' => 'success'])
Go to My Dashboard
@endcomponent

**What you can do now:**
- 📅 Manage your appointments
- 🗓️ Set your weekly schedule
- 👥 View your patients
- 📝 Add medical notes
- 🏖️ Request leave when needed

Welcome to the One Health team!

Thanks,
**One Health Team**

@endcomponent
