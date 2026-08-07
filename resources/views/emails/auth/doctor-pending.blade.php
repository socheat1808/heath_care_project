@component('mail::message')

# Registration Received ⏳

Hello **{{ $user->name }}**,

Thank you for registering as a doctor on One Health. Your account is currently **pending admin approval**.

@component('mail::panel')
**What happens next?**

Our admin team will review your registration and approve your account. This usually takes **1-2 business days**.

You will receive another email once your account is approved.
@endcomponent

**While you wait:**
- Make sure your registration details are correct
- Prepare your medical license information
- Our team may contact you for verification

Thanks,
**One Health Team**

@endcomponent
