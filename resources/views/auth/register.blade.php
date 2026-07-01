<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - one Healthcare</title>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
    <script src="{{ asset('assets/js/auth.js') }}"></script>
</head>

<body>
    <div class="auth-wrap">

        <!-- LEFT PANEL -->
        <aside class="auth-panel">
            ...
        </aside>

        <!-- RIGHT FORM PANEL -->
        <main class="auth-form-wrap">
            <div class="auth-form-box">

                <div class="form-header">
                    <span class="eyebrow">New Account</span>
                    <h1>Create your account</h1>
                    <p>Start your journey to better health today — it's completely free.</p>
                </div>

                <!-- Errors -->
                @if ($errors->any())
                <div class="alert-error">
                    @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- NAME -->
                    <div class="field-group">
                        <x-input-label for="name" :value="__('Name')" />
                        <div class="field-wrap">
                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                autocomplete="name"
                                class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                                placeholder="John Doe">
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- EMAIL -->
                    <div class="field-group mt-3">
                        <x-input-label for="email" :value="__('Email')" />
                        <div class="field-wrap">
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="username"
                                class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                                placeholder="you@example.com">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- PHONE -->
                    <div class="field-group mt-3">
                        <x-input-label for="phone" :value="__('Phone')" />
                        <div class="field-wrap">
                            <input
                                id="phone"
                                type="text"
                                name="phone"
                                value="{{ old('phone') }}"
                                required
                                autocomplete="tel"
                                class="{{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                placeholder="012 345 678">
                        </div>
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <!-- PASSWORD -->
                    <div class="field-group mt-3">
                        <x-input-label for="password" :value="__('Password')" />
                        <div class="field-wrap">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                                placeholder="Create password">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- CONFIRM PASSWORD -->
                    <div class="field-group mt-3">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <div class="field-wrap">
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Repeat password">
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- USER TYPE -->
                    <div class="field-group mt-3">
                        <label for="role">Register as</label>
                        <div class="field-wrap">
                            <select
                                id="role"
                                name="role"
                                required
                                class="{{ $errors->has('role') ? 'is-invalid' : '' }}">
                                <option value="">-- Select Role --</option>
                                <option value="patient" {{ old('role') == 'patient' ? 'selected' : '' }}>Patient</option>
                                <option value="doctor" {{ old('role') == 'doctor'  ? 'selected' : '' }}>Doctor</option>
                            </select>
                        </div>
                        @error('role')
                        <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- SPECIALIZATION (doctor only) -->
                    <div class="field-group mt-3" id="specialization-field" style="display:{{ old('role') == 'doctor' ? 'block' : 'none' }};">
                        <x-input-label for="specialization" :value="__('Specialization')" />
                        <div class="field-wrap">
                            <select
                                id="specialization"
                                name="specialization"
                                class="{{ $errors->has('specialization') ? 'is-invalid' : '' }}">
                                <option value="">-- Select Specialization --</option>
                                @foreach([
                                'General Health',
                                'Cardiology',
                                'Dental',
                                'Neurology',
                                'Orthopaedics',
                                'Dermatology',
                                'Pediatrics',
                                'Psychiatry',
                                'Ophthalmology'
                                ] as $s)
                                <option value="{{ $s }}" {{ old('specialization') == $s ? 'selected' : '' }}>
                                    {{ $s }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <x-input-error :messages="$errors->get('specialization')" class="mt-2" />
                    </div>

                    <!-- SUBMIT -->
                    <button type="submit" class="btn-submit mt-4">
                        {{ __('Register') }}
                    </button>

                </form>

                <p class="form-footer">
                    Already registered?
                    <a href="{{ route('login') }}">Sign in</a>
                </p>

            </div>
        </main>

    </div>

    <script>
        const roleSelect = document.getElementById('role');
        const specField = document.getElementById('specialization-field');

        roleSelect.addEventListener('change', function() {
            specField.style.display = this.value === 'doctor' ? 'block' : 'none';
            if (this.value !== 'doctor') {
                document.getElementById('specialization').value = '';
            }
        });
    </script>

</body>

</html>