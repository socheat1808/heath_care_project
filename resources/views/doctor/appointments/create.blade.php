@extends('doctor.layout')
@section('title', 'New Appointment')
@section('content')

@php
$user = Auth::user();
$doctor = \App\Models\Doctor::where('email', $user->email)->first();
@endphp

{{-- ── Page Header ────────────────────────────────────────── --}}
<div class="page-header">
    <div>
        <h1>New Appointment</h1>
        <p>Schedule a new appointment for your patient.</p>
    </div>
    <a href="{{ route('doctor.appointments.index') }}" class="btn-primary"
        style="background:var(--bg);color:var(--text);border:1px solid var(--border);box-shadow:none">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Appointments
    </a>
</div>

{{-- ── Validation Errors ───────────────────────────────────── --}}
@if($errors->any())
<div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.25rem;display:flex;gap:.75rem;align-items:flex-start">
    <svg fill="none" viewBox="0 0 24 24" stroke="#ef4444" stroke-width="2" style="width:18px;height:18px;flex-shrink:0;margin-top:.1rem">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <div>
        <div style="font-size:.875rem;font-weight:600;color:#b91c1c;margin-bottom:.35rem">Please fix the following errors:</div>
        <ul style="margin:0;padding-left:1.1rem;font-size:.83rem;color:#dc2626">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

{{-- ── Form Card ───────────────────────────────────────────── --}}
<div class="chart-card">

    <div style="margin-bottom:1.5rem">
        <div class="card-title">Appointment Details</div>
        <div class="card-subtitle">Fill in all required fields marked with *</div>
    </div>

    <form method="POST" action="{{ route('doctor.appointments.store') }}">
        @csrf

        {{-- hidden doctor id --}}
        <input type="hidden" name="doctor_id" value="{{ optional($doctor)->DoctorID }}">

        {{-- ── Row 1: Patient + Department ── --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem">

            {{-- Patient --}}
            <div>
                <label style="display:block;font-size:.83rem;font-weight:600;color:var(--text-muted);margin-bottom:.45rem">
                    Patient <span style="color:#ef4444">*</span>
                </label>
                <select name="patient_id" required
                    style="width:100%;padding:.6rem .9rem;border:1px solid {{ $errors->has('patient_id') ? '#ef4444' : 'var(--border)' }};
                           border-radius:10px;background:var(--bg);color:var(--text);font-size:.875rem;outline:none;
                           transition:border-color .15s"
                    onfocus="this.style.borderColor='var(--green)'"
                    onblur="this.style.borderColor='{{ $errors->has('patient_id') ? '#ef4444' : 'var(--border)' }}'">
                    <option value="">— Select patient —</option>
                    @foreach($patients as $patient)
                    <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                        {{ $patient->name }}
                    </option>
                    @endforeach
                </select>
                @error('patient_id')
                <div style="font-size:.76rem;color:#ef4444;margin-top:.3rem">{{ $message }}</div>
                @enderror
            </div>

            {{-- Department --}}
            <div>
                <label style="display:block;font-size:.83rem;font-weight:600;color:var(--text-muted);margin-bottom:.45rem">
                    Department <span style="color:#ef4444">*</span>
                </label>
                <select name="department" required
                    style="width:100%;padding:.6rem .9rem;border:1px solid {{ $errors->has('department') ? '#ef4444' : 'var(--border)' }};
                           border-radius:10px;background:var(--bg);color:var(--text);font-size:.875rem;outline:none;
                           transition:border-color .15s"
                    onfocus="this.style.borderColor='var(--green)'"
                    onblur="this.style.borderColor='{{ $errors->has('department') ? '#ef4444' : 'var(--border)' }}'">
                    <option value="">— Select department —</option>
                    @foreach(['General', 'Cardiology', 'Neurology', 'Orthopedics', 'Pediatrics', 'Dermatology', 'Ophthalmology', 'ENT', 'Gynecology', 'Urology'] as $dept)
                    <option value="{{ $dept }}" {{ old('department') === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>
                @error('department')
                <div style="font-size:.76rem;color:#ef4444;margin-top:.3rem">{{ $message }}</div>
                @enderror
            </div>

        </div>

        {{-- ── Row 2: Date + Time ── --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem">

            {{-- Date --}}
            <div>
                <label style="display:block;font-size:.83rem;font-weight:600;color:var(--text-muted);margin-bottom:.45rem">
                    Appointment Date <span style="color:#ef4444">*</span>
                </label>
                <input type="date" name="appointment_date"
                    value="{{ old('appointment_date') }}"
                    min="{{ now()->toDateString() }}"
                    required
                    style="width:100%;padding:.6rem .9rem;border:1px solid {{ $errors->has('appointment_date') ? '#ef4444' : 'var(--border)' }};
                              border-radius:10px;background:var(--bg);color:var(--text);font-size:.875rem;outline:none;
                              transition:border-color .15s;box-sizing:border-box"
                    onfocus="this.style.borderColor='var(--green)'"
                    onblur="this.style.borderColor='{{ $errors->has('appointment_date') ? '#ef4444' : 'var(--border)' }}'">
                @error('appointment_date')
                <div style="font-size:.76rem;color:#ef4444;margin-top:.3rem">{{ $message }}</div>
                @enderror
            </div>

            {{-- Time --}}
            <div>
                <label style="display:block;font-size:.83rem;font-weight:600;color:var(--text-muted);margin-bottom:.45rem">
                    Appointment Time <span style="color:#ef4444">*</span>
                </label>
                <input type="time" name="appointment_time"
                    value="{{ old('appointment_time') }}"
                    required
                    style="width:100%;padding:.6rem .9rem;border:1px solid {{ $errors->has('appointment_time') ? '#ef4444' : 'var(--border)' }};
                              border-radius:10px;background:var(--bg);color:var(--text);font-size:.875rem;outline:none;
                              transition:border-color .15s;box-sizing:border-box"
                    onfocus="this.style.borderColor='var(--green)'"
                    onblur="this.style.borderColor='{{ $errors->has('appointment_time') ? '#ef4444' : 'var(--border)' }}'">
                @error('appointment_time')
                <div style="font-size:.76rem;color:#ef4444;margin-top:.3rem">{{ $message }}</div>
                @enderror
            </div>

        </div>

        {{-- ── Row 3: Status + Type ── --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem">

            {{-- Status --}}
            <div>
                <label style="display:block;font-size:.83rem;font-weight:600;color:var(--text-muted);margin-bottom:.45rem">
                    Status <span style="color:#ef4444">*</span>
                </label>
                <select name="status" required
                    style="width:100%;padding:.6rem .9rem;border:1px solid {{ $errors->has('status') ? '#ef4444' : 'var(--border)' }};
                           border-radius:10px;background:var(--bg);color:var(--text);font-size:.875rem;outline:none;
                           transition:border-color .15s"
                    onfocus="this.style.borderColor='var(--green)'"
                    onblur="this.style.borderColor='{{ $errors->has('status') ? '#ef4444' : 'var(--border)' }}'">
                    <option value="pending" {{ old('status','pending') === 'pending'   ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ old('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status')
                <div style="font-size:.76rem;color:#ef4444;margin-top:.3rem">{{ $message }}</div>
                @enderror
            </div>

            {{-- Visit Type --}}
            <div>
                <label style="display:block;font-size:.83rem;font-weight:600;color:var(--text-muted);margin-bottom:.45rem">
                    Visit Type
                </label>
                <select name="visit_type"
                    style="width:100%;padding:.6rem .9rem;border:1px solid var(--border);
                           border-radius:10px;background:var(--bg);color:var(--text);font-size:.875rem;outline:none;
                           transition:border-color .15s"
                    onfocus="this.style.borderColor='var(--green)'"
                    onblur="this.style.borderColor='var(--border)'">
                    <option value="in-person" {{ old('visit_type','in-person') === 'in-person'  ? 'selected' : '' }}>In-Person</option>
                    <option value="telemedicine" {{ old('visit_type') === 'telemedicine' ? 'selected' : '' }}>Telemedicine</option>
                    <option value="follow-up" {{ old('visit_type') === 'follow-up'   ? 'selected' : '' }}>Follow-Up</option>
                    <option value="emergency" {{ old('visit_type') === 'emergency'   ? 'selected' : '' }}>Emergency</option>
                </select>
                @error('visit_type')
                <div style="font-size:.76rem;color:#ef4444;margin-top:.3rem">{{ $message }}</div>
                @enderror
            </div>

        </div>

        {{-- ── Notes ── --}}
        <div style="margin-bottom:1.5rem">
            <label style="display:block;font-size:.83rem;font-weight:600;color:var(--text-muted);margin-bottom:.45rem">
                Notes / Reason for Visit
            </label>
            <textarea name="notes" rows="4"
                placeholder="Describe the reason for this appointment or any additional notes…"
                style="width:100%;padding:.7rem .9rem;border:1px solid {{ $errors->has('notes') ? '#ef4444' : 'var(--border)' }};
                             border-radius:10px;background:var(--bg);color:var(--text);font-size:.875rem;
                             outline:none;resize:vertical;font-family:inherit;transition:border-color .15s;box-sizing:border-box"
                onfocus="this.style.borderColor='var(--green)'"
                onblur="this.style.borderColor='{{ $errors->has('notes') ? '#ef4444' : 'var(--border)' }}'">{{ old('notes') }}</textarea>
            @error('notes')
            <div style="font-size:.76rem;color:#ef4444;margin-top:.3rem">{{ $message }}</div>
            @enderror
        </div>

        {{-- ── Divider ── --}}
        <div style="border-top:1px solid var(--border);margin-bottom:1.25rem"></div>

        {{-- ── Actions ── --}}
        <div style="display:flex;gap:.75rem;justify-content:flex-end">
            <a href="{{ route('doctor.appointments.index') }}"
                style="padding:.6rem 1.4rem;border:1px solid var(--border);border-radius:10px;
                      color:var(--text-muted);text-decoration:none;font-size:.875rem;font-weight:600;
                      background:var(--bg);transition:all .15s"
                onmouseover="this.style.borderColor='var(--text-muted)'"
                onmouseout="this.style.borderColor='var(--border)'">
                Cancel
            </a>
            <button type="submit" class="btn-primary" style="padding:.6rem 1.6rem;font-size:.875rem">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:15px;height:15px">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Save Appointment
            </button>
        </div>

    </form>
</div>

@endsection