{{-- resources/views/patient/book-appointment.blade.php --}}
@extends('patient.layout')
@section('title', 'Book Appointment')
@section('content')

{{-- ── Page Header ── --}}
<div class="page-header">
    <div>
        <h1>Book Appointment</h1>
        <p>Schedule a visit with Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}.</p>
    </div>
    <a href="{{ route('patient.appointments.find-doctors') }}"
        style="display:inline-flex;align-items:center;gap:.45rem;padding:.55rem 1.1rem;
              border:1px solid var(--border);border-radius:10px;color:var(--text-muted);
              text-decoration:none;font-size:.85rem;font-weight:600;background:var(--bg)">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Doctors
    </a>
</div>

{{-- ── Validation errors ── --}}
@if($errors->any())
<div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:1rem 1.25rem;
            margin-bottom:1.25rem;display:flex;gap:.75rem;align-items:flex-start">
    <svg fill="none" viewBox="0 0 24 24" stroke="#ef4444" stroke-width="2" style="width:18px;height:18px;flex-shrink:0;margin-top:.1rem">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <div>
        <div style="font-size:.875rem;font-weight:600;color:#b91c1c;margin-bottom:.35rem">Please fix the following:</div>
        <ul style="margin:0;padding-left:1.1rem;font-size:.83rem;color:#dc2626">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<div style="display:grid;grid-template-columns:1fr 340px;gap:1.25rem;align-items:start">

    {{-- ── Booking Form ── --}}
    <div class="chart-card">
        <div style="margin-bottom:1.5rem">
            <div class="card-title">Appointment Details</div>
            <div class="card-subtitle">Fill in all required fields marked with *</div>
        </div>

        <form method="POST" action="{{ route('patient.appointments.store') }}">
            @csrf
            <input type="hidden" name="doctor_id" value="{{ $doctor->DoctorID }}">

            {{-- Date + Time --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem">
                <div>
                    <label style="display:block;font-size:.83rem;font-weight:600;color:var(--text-muted);margin-bottom:.45rem">
                        Date <span style="color:#ef4444">*</span>
                    </label>
                    <input type="date" name="appointment_date"
                        value="{{ old('appointment_date') }}"
                        min="{{ now()->addDay()->toDateString() }}"
                        required
                        @if($doctor->status !== 'available') disabled @endif
                    style="width:100%;padding:.6rem .9rem;border:1px solid {{ $errors->has('appointment_date') ? '#ef4444' : 'var(--border)' }};
                    border-radius:10px;background:var(--bg);color:var(--text);font-size:.875rem;
                    outline:none;box-sizing:border-box"
                    onfocus="this.style.borderColor='var(--green)'"
                    onblur="this.style.borderColor='var(--border)'">
                    @error('appointment_date')
                    <div style="font-size:.76rem;color:#ef4444;margin-top:.3rem">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label style="display:block;font-size:.83rem;font-weight:600;color:var(--text-muted);margin-bottom:.45rem">
                        Time <span style="color:#ef4444">*</span>
                    </label>
                    @if($availableSlots && count($availableSlots))
                    <select name="appointment_time" required
                        @if($doctor->status !== 'available') disabled @endif
                        style="width:100%;padding:.6rem .9rem;border:1px solid var(--border);border-radius:10px;
                        background:var(--bg);color:var(--text);font-size:.875rem;outline:none"
                        onfocus="this.style.borderColor='var(--green)'"
                        onblur="this.style.borderColor='var(--border)'">
                        <option value="">— Pick a time —</option>
                        @foreach($availableSlots as $slot)
                        <option value="{{ $slot }}" {{ old('appointment_time') === $slot ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::parse($slot)->format('h:i A') }}
                        </option>
                        @endforeach
                    </select>
                    @else
                    <input type="time" name="appointment_time"
                        value="{{ old('appointment_time') }}"
                        required
                        @if($doctor->status !== 'available') disabled @endif
                    style="width:100%;padding:.6rem .9rem;border:1px solid var(--border);border-radius:10px;
                    background:var(--bg);color:var(--text);font-size:.875rem;outline:none;box-sizing:border-box"
                    onfocus="this.style.borderColor='var(--green)'"
                    onblur="this.style.borderColor='var(--border)'">
                    @endif
                    @error('appointment_time')
                    <div style="font-size:.76rem;color:#ef4444;margin-top:.3rem">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Department --}}
            <div style="margin-bottom:1.25rem">
                <label style="display:block;font-size:.83rem;font-weight:600;color:var(--text-muted);margin-bottom:.45rem">
                    Department <span style="color:#ef4444">*</span>
                </label>
                <select name="department" required
                    @if($doctor->status !== 'available') disabled @endif
                    style="width:100%;padding:.6rem .9rem;border:1px solid var(--border);border-radius:10px;
                    background:var(--bg);color:var(--text);font-size:.875rem;outline:none"
                    onfocus="this.style.borderColor='var(--green)'"
                    onblur="this.style.borderColor='var(--border)'">
                    <option value="{{ $doctor->specialization }}" selected>{{ $doctor->specialization }}</option>
                    @foreach(['General','Cardiology','Neurology','Orthopedics','Pediatrics','Dermatology','Ophthalmology','ENT','Gynecology','Urology'] as $dept)
                    @if($dept !== $doctor->specialization)
                    <option value="{{ $dept }}" {{ old('department') === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endif
                    @endforeach
                </select>
            </div>

            {{-- Visit type --}}
            <div style="margin-bottom:1.25rem">
                <label style="display:block;font-size:.83rem;font-weight:600;color:var(--text-muted);margin-bottom:.45rem">
                    Visit Type
                </label>
                <div style="display:flex;gap:.65rem;flex-wrap:wrap">
                    @foreach(['in-person' => 'In-Person', 'telemedicine' => 'Telemedicine', 'follow-up' => 'Follow-Up'] as $val => $label)
                    <label style="display:flex;align-items:center;gap:.4rem;cursor:pointer;font-size:.875rem;
                                  padding:.5rem .9rem;border:1px solid var(--border);border-radius:8px;background:var(--bg)">
                        <input type="radio" name="visit_type" value="{{ $val }}"
                            {{ old('visit_type','in-person') === $val ? 'checked' : '' }}
                            style="accent-color:var(--green)">
                        {{ $label }}
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Notes --}}
            <div style="margin-bottom:1.5rem">
                <label style="display:block;font-size:.83rem;font-weight:600;color:var(--text-muted);margin-bottom:.45rem">
                    Reason / Notes
                </label>
                <textarea name="notes" rows="4"
                    placeholder="Describe your symptoms or reason for visiting…"
                    @if($doctor->status !== 'available') disabled @endif
                          style="width:100%;padding:.7rem .9rem;border:1px solid var(--border);border-radius:10px;
                                 background:var(--bg);color:var(--text);font-size:.875rem;outline:none;
                                 resize:vertical;font-family:inherit;box-sizing:border-box"
                          onfocus="this.style.borderColor='var(--green)'"
                          onblur="this.style.borderColor='var(--border)'">{{ old('notes') }}</textarea>
            </div>

            <div style="border-top:1px solid var(--border);padding-top:1.25rem;display:flex;gap:.75rem;justify-content:flex-end">
                <a href="{{ route('patient.appointments.find-doctors') }}"
                    style="padding:.6rem 1.4rem;border:1px solid var(--border);border-radius:10px;
                          color:var(--text-muted);text-decoration:none;font-size:.875rem;font-weight:600">
                    Cancel
                </a>
                <button type="submit"
                    @if($doctor->status !== 'available') disabled @endif
                    class="btn-primary"
                    style="opacity:{{ $doctor->status !== 'available' ? '.5' : '1' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:15px;height:15px">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Request Appointment
                </button>
            </div>
        </form>
    </div>

    {{-- ── Doctor Summary Sidebar ── --}}
    <div style="display:flex;flex-direction:column;gap:1rem">

        {{-- Doctor card --}}
        <div class="chart-card" style="margin-bottom:0">
            <div class="card-title" style="margin-bottom:1rem">Doctor Info</div>

            <div style="display:flex;align-items:center;gap:.85rem;padding:1rem;
                        background:var(--bg);border-radius:12px;margin-bottom:1rem">
                <div style="width:48px;height:48px;border-radius:50%;background:var(--green);color:#fff;
                            display:flex;align-items:center;justify-content:center;font-size:1rem;font-weight:700;flex-shrink:0">
                    {{ strtoupper(substr($doctor->first_name,0,1).substr($doctor->last_name,0,1)) }}
                </div>
                <div>
                    <div style="font-size:.95rem;font-weight:700;color:var(--text)">
                        Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                    </div>
                    <div style="font-size:.78rem;color:var(--text-muted)">{{ $doctor->specialization }}</div>
                    @if($doctor->status === 'available')
                    <span class="badge badge-green" style="margin-top:.3rem">● Available</span>
                    @else
                    <span class="badge badge-red" style="margin-top:.3rem">● Unavailable</span>
                    @endif
                </div>
            </div>

            <div style="display:flex;flex-direction:column;gap:.5rem;font-size:.83rem">
                <div style="display:flex;justify-content:space-between;padding:.45rem 0;border-bottom:1px solid var(--border)">
                    <span style="color:var(--text-muted)">Experience</span>
                    <span style="font-weight:600;color:var(--text)">{{ $doctor->years_of_experience }} yrs</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:.45rem 0;border-bottom:1px solid var(--border)">
                    <span style="color:var(--text-muted)">Consultation Fee</span>
                    <span style="font-weight:600;color:var(--text)">${{ number_format($doctor->consultation_fee, 0) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:.45rem 0">
                    <span style="color:var(--text-muted)">Department</span>
                    <span style="font-weight:600;color:var(--text)">{{ $doctor->specialization }}</span>
                </div>
            </div>
        </div>

        {{-- Unavailable warning --}}
        @if($doctor->status !== 'available')
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:1rem;
                    font-size:.83rem;color:#b91c1c;display:flex;gap:.6rem;align-items:flex-start">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;flex-shrink:0;margin-top:.1rem">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            This doctor is currently {{ $doctor->status === 'onleave' ? 'on leave' : 'unavailable' }} and not accepting appointments.
        </div>
        @endif

        {{-- Working days --}}
        @if($schedules && $schedules->where('is_active', true)->count())
        <div class="chart-card" style="margin-bottom:0">
            <div class="card-title" style="margin-bottom:.85rem">Working Days</div>
            @foreach($schedules->where('is_active', true) as $s)
            <div style="display:flex;justify-content:space-between;font-size:.82rem;
                        padding:.4rem 0;border-bottom:1px solid var(--border)">
                <span style="color:var(--text);font-weight:600">{{ $s->day }}</span>
                <span style="color:var(--text-muted)">
                    {{ \Carbon\Carbon::parse($s->start_time)->format('h:i A') }}
                    –
                    {{ \Carbon\Carbon::parse($s->end_time)->format('h:i A') }}
                </span>
            </div>
            @endforeach
        </div>
        @endif

    </div>
</div>

@endsection