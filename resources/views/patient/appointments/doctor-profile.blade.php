{{-- resources/views/patients-dashboard/appointments/doctor-profile.blade.php --}}
@extends('patient.layout')
@section('title', 'Doctor Profile')
@section('content')

{{-- ── Page Header ── --}}
<div class="page-header">
    <div>
        <h1>Doctor Profile</h1>
        <p>View details and book an appointment with this doctor.</p>
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

<div style="display:grid;grid-template-columns:1fr 320px;gap:1.25rem;align-items:start">

    {{-- ── Left: Main Profile ── --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem">

        {{-- Doctor Hero Card --}}
        <div class="chart-card" style="margin-bottom:0">
            <div style="display:flex;align-items:center;gap:1.25rem;flex-wrap:wrap">

                {{-- Avatar --}}
                <div style="width:80px;height:80px;border-radius:50%;
                            background:{{ $doctor->status === 'available' ? 'var(--green)' : '#9ca3af' }};
                            color:#fff;display:flex;align-items:center;justify-content:center;
                            font-size:1.6rem;font-weight:700;flex-shrink:0">
                    {{ strtoupper(substr($doctor->first_name,0,1).substr($doctor->last_name,0,1)) }}
                </div>

                {{-- Name & Meta --}}
                <div style="flex:1;min-width:0">
                    <div style="display:flex;align-items:center;gap:.65rem;flex-wrap:wrap;margin-bottom:.3rem">
                        <h2 style="margin:0;font-size:1.25rem;font-weight:700;color:var(--text)">
                            Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                        </h2>
                        @if($doctor->status === 'available')
                        <span class="badge badge-green">● Available</span>
                        @elseif($doctor->status === 'onleave')
                        <span class="badge badge-amber">● On Leave</span>
                        @else
                        <span class="badge badge-red">● Unavailable</span>
                        @endif
                    </div>
                    <div style="font-size:.9rem;color:var(--text-muted);margin-bottom:.65rem">
                        {{ $doctor->specialization }}
                    </div>

                    {{-- Stats row --}}
                    <div style="display:flex;gap:1.5rem;flex-wrap:wrap;font-size:.83rem;color:var(--text-muted)">
                        <div style="display:flex;align-items:center;gap:.35rem">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $doctor->years_of_experience ?? 0 }} yrs experience
                        </div>
                        <div style="display:flex;align-items:center;gap:.35rem">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            ${{ number_format($doctor->consultation_fee, 0) }} / visit
                        </div>
                        @if($doctor->email)
                        <div style="display:flex;align-items:center;gap:.35rem">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ $doctor->email }}
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Book Now (desktop inline) --}}
                <div style="flex-shrink:0">
                    @if($doctor->status === 'available')
                    <a href="{{ route('patient.appointments.book', $doctor->DoctorID) }}"
                        class="btn-primary" style="text-decoration:none;white-space:nowrap">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:15px;height:15px">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Book Appointment
                    </a>
                    @else
                    <button disabled
                        style="display:inline-flex;align-items:center;gap:.45rem;padding:.55rem 1.1rem;
                               border-radius:10px;background:#f3f4f6;color:#9ca3af;border:1px solid var(--border);
                               font-size:.875rem;font-weight:600;cursor:not-allowed">
                        Not Available
                    </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Unavailable Warning --}}
        @if($doctor->status !== 'available')
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:1rem 1.25rem;
                    font-size:.83rem;color:#b91c1c;display:flex;gap:.65rem;align-items:flex-start">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;flex-shrink:0;margin-top:.1rem">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            This doctor is currently {{ $doctor->status === 'onleave' ? 'on leave' : 'unavailable' }} and not accepting new appointments at this time.
        </div>
        @endif

        {{-- About / Biography --}}
        @if($doctor->biography_note)
        <div class="chart-card" style="margin-bottom:0">
            <div class="card-title" style="margin-bottom:.85rem">About</div>
            <p style="font-size:.875rem;color:var(--text-muted);line-height:1.7;margin:0">
                {{ $doctor->biography_note }}
            </p>
        </div>
        @endif

        {{-- Working Schedule --}}
        @if($schedules && $schedules->where('is_active', true)->count())
        <div class="chart-card" style="margin-bottom:0">
            <div class="card-title" style="margin-bottom:1rem">Working Schedule</div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:.65rem">
                @foreach($schedules->where('is_active', true) as $s)
                <div style="display:flex;justify-content:space-between;align-items:center;
                            padding:.65rem .9rem;border:1px solid var(--border);border-radius:10px;
                            background:var(--bg);font-size:.83rem">
                    <span style="font-weight:600;color:var(--text)">{{ $s->day }}</span>
                    <span style="color:var(--text-muted)">
                        {{ \Carbon\Carbon::parse($s->start_time)->format('h:i A') }}
                        –
                        {{ \Carbon\Carbon::parse($s->end_time)->format('h:i A') }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- ── Right: Sidebar ── --}}
    <div style="display:flex;flex-direction:column;gap:1rem">

        {{-- Quick Info --}}
        <div class="chart-card" style="margin-bottom:0">
            <div class="card-title" style="margin-bottom:1rem">Quick Info</div>
            <div style="display:flex;flex-direction:column;gap:.5rem;font-size:.83rem">

                <div style="display:flex;justify-content:space-between;padding:.45rem 0;border-bottom:1px solid var(--border)">
                    <span style="color:var(--text-muted)">Specialization</span>
                    <span style="font-weight:600;color:var(--text)">{{ $doctor->specialization }}</span>
                </div>

                <div style="display:flex;justify-content:space-between;padding:.45rem 0;border-bottom:1px solid var(--border)">
                    <span style="color:var(--text-muted)">Experience</span>
                    <span style="font-weight:600;color:var(--text)">{{ $doctor->years_of_experience ?? 0 }} years</span>
                </div>

                <div style="display:flex;justify-content:space-between;padding:.45rem 0;border-bottom:1px solid var(--border)">
                    <span style="color:var(--text-muted)">Consultation Fee</span>
                    <span style="font-weight:600;color:var(--text)">${{ number_format($doctor->consultation_fee, 0) }}</span>
                </div>

                <div style="display:flex;justify-content:space-between;padding:.45rem 0;border-bottom:1px solid var(--border)">
                    <span style="color:var(--text-muted)">Status</span>
                    @if($doctor->status === 'available')
                    <span style="font-weight:600;color:#16a34a">Available</span>
                    @elseif($doctor->status === 'onleave')
                    <span style="font-weight:600;color:#d97706">On Leave</span>
                    @else
                    <span style="font-weight:600;color:#dc2626">Unavailable</span>
                    @endif
                </div>

                @if($schedules && $schedules->where('is_active', true)->count())
                <div style="display:flex;justify-content:space-between;padding:.45rem 0">
                    <span style="color:var(--text-muted)">Working Days</span>
                    <span style="font-weight:600;color:var(--text)">
                        {{ $schedules->where('is_active', true)->count() }} days/week
                    </span>
                </div>
                @endif

            </div>
        </div>

        {{-- Book CTA --}}
        <div class="chart-card" style="margin-bottom:0;text-align:center">
            <div style="font-size:2rem;margin-bottom:.5rem">📅</div>
            <div style="font-size:.9rem;font-weight:700;color:var(--text);margin-bottom:.35rem">
                Ready to book?
            </div>
            <div style="font-size:.8rem;color:var(--text-muted);margin-bottom:1rem">
                Schedule your visit with Dr. {{ $doctor->last_name }} today.
            </div>

            @if($doctor->status === 'available')
            <a href="{{ route('patient.appointments.book', $doctor->DoctorID) }}"
                class="btn-primary"
                style="display:flex;justify-content:center;text-decoration:none;width:100%;box-sizing:border-box">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:15px;height:15px">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Book Appointment
            </a>
            @else
            <div style="font-size:.8rem;color:#b91c1c;background:#fef2f2;border:1px solid #fecaca;
                        border-radius:10px;padding:.65rem;font-weight:600">
                Not accepting appointments
            </div>
            @endif
        </div>

        {{-- Back to list --}}
        <a href="{{ route('patient.appointments.find-doctors') }}"
            style="display:flex;align-items:center;justify-content:center;gap:.4rem;
                  padding:.65rem;border:1px dashed var(--border);border-radius:10px;
                  color:var(--text-muted);text-decoration:none;font-size:.83rem;font-weight:600"
            onmouseover="this.style.borderColor='var(--green)';this.style.color='var(--green)'"
            onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:13px;height:13px">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Browse other doctors
        </a>

    </div>
</div>

@endsection