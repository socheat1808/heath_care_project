@extends('admin.layout')
@section('title', 'Doctor Profile')
@section('content')

{{-- ── Page Header ── --}}
<div class="page-header">
    <div class="page-header-left">
        <h1>Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</h1>
        <p>Full profile, schedule and appointment history.</p>
    </div>
    <div style="display:flex;gap:.65rem">
        <a href="{{ route('admin.doctors.edit', $doctor->DoctorID) }}"
            class="btn-primary"
            style="text-decoration:none;display:inline-flex;align-items:center;gap:.45rem;
                   padding:.55rem 1.1rem;font-size:.85rem">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit Doctor
        </a>
        <a href="{{ route('admin.doctors.index') }}"
            style="display:inline-flex;align-items:center;gap:.45rem;padding:.55rem 1.1rem;
                   border:1px solid var(--border);border-radius:10px;color:var(--text-muted);
                   text-decoration:none;font-size:.85rem;font-weight:600;background:var(--bg)">
            ← Back
        </a>
    </div>
</div>

{{-- ── Stat Cards ── --}}
<div class="stats-grid" style="margin-bottom:1.25rem">

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon green">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <span class="stat-trend up">Total</span>
        </div>
        <div class="stat-value">{{ $totalAppointments }}</div>
        <div class="stat-label">Total Appointments</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon blue">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <span class="stat-trend up">Unique</span>
        </div>
        <div class="stat-value">{{ $totalPatients }}</div>
        <div class="stat-label">Total Patients</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon purple">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="stat-trend up">Done</span>
        </div>
        <div class="stat-value">{{ $completedAppointments }}</div>
        <div class="stat-label">Completed</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon amber">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="stat-trend down">Waiting</span>
        </div>
        <div class="stat-value">{{ $pendingAppointments }}</div>
        <div class="stat-label">Pending</div>
    </div>

</div>

{{-- ── Profile + Schedule ── --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem">

    {{-- Doctor Profile --}}
    <div class="chart-card" style="margin-bottom:0">
        <div class="card-title" style="margin-bottom:1rem">Doctor Information</div>

        <div style="display:flex;align-items:center;gap:1rem;padding:1rem;
                    background:var(--bg);border-radius:12px;margin-bottom:1.25rem">
            {{-- Photo or initials --}}
            @if($doctor->photo)
            <img src="{{ asset('storage/'.$doctor->photo) }}"
                style="width:64px;height:64px;border-radius:50%;object-fit:cover;flex-shrink:0">
            @else
            <div style="width:64px;height:64px;border-radius:50%;background:var(--green);color:#fff;
                        display:flex;align-items:center;justify-content:center;
                        font-size:1.3rem;font-weight:700;flex-shrink:0">
                {{ strtoupper(substr($doctor->first_name,0,1).substr($doctor->last_name,0,1)) }}
            </div>
            @endif
            <div>
                <div style="font-size:1rem;font-weight:700;color:var(--text)">
                    Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                </div>
                <div style="font-size:.8rem;color:var(--text-muted)">{{ $doctor->specialization }}</div>
                @if($doctor->status === 'available')
                <span class="badge badge-green" style="margin-top:.3rem">● Available</span>
                @elseif($doctor->status === 'onleave')
                <span class="badge badge-amber" style="margin-top:.3rem">● On Leave</span>
                @else
                <span class="badge badge-red" style="margin-top:.3rem">● Unavailable</span>
                @endif
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:.5rem">
            <div style="display:flex;justify-content:space-between;font-size:.875rem;
                        padding:.5rem 0;border-bottom:1px solid var(--border)">
                <span style="color:var(--text-muted)">Email</span>
                <span style="font-weight:600;color:var(--text)">{{ $doctor->email }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:.875rem;
                        padding:.5rem 0;border-bottom:1px solid var(--border)">
                <span style="color:var(--text-muted)">Phone</span>
                <span style="font-weight:600;color:var(--text)">{{ $doctor->phone ?? '—' }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:.875rem;
                        padding:.5rem 0;border-bottom:1px solid var(--border)">
                <span style="color:var(--text-muted)">Experience</span>
                <span style="font-weight:600;color:var(--text)">{{ $doctor->years_of_experience }} years</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:.875rem;
                        padding:.5rem 0;border-bottom:1px solid var(--border)">
                <span style="color:var(--text-muted)">Consultation Fee</span>
                <span style="font-weight:600;color:var(--text)">${{ number_format($doctor->consultation_fee, 0) }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:.875rem;padding:.5rem 0">
                <span style="color:var(--text-muted)">Specialization</span>
                <span style="font-weight:600;color:var(--text)">{{ $doctor->specialization }}</span>
            </div>
        </div>

        @if($doctor->biography_note)
        <div style="margin-top:1rem;padding:.85rem 1rem;background:var(--bg);
                    border-radius:8px;font-size:.83rem;color:var(--text-muted);line-height:1.6">
            {{ $doctor->biography_note }}
        </div>
        @endif
    </div>

    {{-- Schedule --}}
    <div class="chart-card" style="margin-bottom:0">
        <div class="card-title" style="margin-bottom:1rem">Weekly Schedule</div>

        @php $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday']; @endphp

        @foreach($days as $day)
        @php $s = $schedules->firstWhere('day', $day); @endphp
        <div style="display:flex;align-items:center;justify-content:space-between;
                    padding:.6rem 0;border-bottom:1px solid var(--border);font-size:.875rem">
            <div style="display:flex;align-items:center;gap:.65rem">
                <div style="width:8px;height:8px;border-radius:50%;flex-shrink:0;
                            background:{{ $s && $s->is_active ? 'var(--green)' : 'var(--border)' }}">
                </div>
                <span style="font-weight:600;color:var(--text);width:90px">{{ $day }}</span>
            </div>
            @if($s && $s->is_active)
            <span style="color:var(--text-muted)">
                {{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }}
                –
                {{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }}
            </span>
            <span class="badge badge-green" style="font-size:.7rem">
                Max {{ $s->max_appointments }} slots
            </span>
            @else
            <span style="color:var(--text-muted);font-size:.8rem">Day off</span>
            @endif
        </div>
        @endforeach
    </div>

</div>

{{-- ── Appointment History ── --}}
<div class="chart-card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem">
        <div>
            <div class="card-title">Appointment History</div>
            <div class="card-subtitle">{{ $appointments->count() }} appointments total</div>
        </div>
    </div>

    @forelse($appointments as $appt)
    @php
    $badgeClass = match($appt->status) {
    'approved' => 'badge-green',
    'pending' => 'badge-amber',
    'completed' => 'badge-blue',
    'cancelled' => 'badge-red',
    'rejected' => 'badge-red',
    default => 'badge-amber',
    };
    @endphp

    <div style="border:1px solid var(--border);border-radius:12px;padding:1rem 1.25rem;
                margin-bottom:.85rem;transition:border-color .15s"
        onmouseover="this.style.borderColor='var(--green)'"
        onmouseout="this.style.borderColor='var(--border)'">

        <div style="display:flex;align-items:center;justify-content:space-between;
                    gap:.75rem;flex-wrap:wrap">

            {{-- Patient info --}}
            <div style="display:flex;align-items:center;gap:.85rem">
                <div style="width:38px;height:38px;border-radius:50%;background:var(--green);color:#fff;
                            display:flex;align-items:center;justify-content:center;
                            font-size:.8rem;font-weight:700;flex-shrink:0">
                    {{ strtoupper(substr($appt->patient->name ?? 'P', 0, 2)) }}
                </div>
                <div>
                    <div style="font-size:.9rem;font-weight:700;color:var(--text)">
                        {{ $appt->patient->name ?? '—' }}
                    </div>
                    <div style="font-size:.78rem;color:var(--text-muted)">
                        {{ $appt->patient->email ?? '' }}
                    </div>
                </div>
            </div>

            <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap">
                <span style="font-size:.83rem;color:var(--text-muted)">
                    {{ \Carbon\Carbon::parse($appt->appointment_date)->format('d M Y') }}
                </span>
                <span style="font-size:.83rem;font-weight:600;color:var(--text)">
                    {{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}
                </span>
                @if($appt->department)
                <span style="font-size:.78rem;color:var(--text-muted)">
                    {{ $appt->department }}
                </span>
                @endif
                <span class="badge {{ $badgeClass }}">{{ ucfirst($appt->status) }}</span>
            </div>
        </div>

        {{-- Rejection reason --}}
        @if($appt->status === 'rejected' && $appt->rejection_reason)
        <div style="margin-top:.65rem;padding:.6rem 1rem;background:#fef2f2;
                    border:1px solid #fecaca;border-radius:8px;font-size:.82rem;color:#b91c1c">
            <span style="font-weight:600">Rejection reason:</span> {{ $appt->rejection_reason }}
        </div>
        @endif

        {{-- Notes --}}
        @if($appt->notes)
        <div style="margin-top:.65rem;padding:.6rem 1rem;background:var(--bg);
                    border:1px solid var(--border);border-radius:8px;font-size:.82rem;color:var(--text-muted)">
            <span style="font-weight:600;color:var(--text)">Notes:</span> {{ $appt->notes }}
        </div>
        @endif
    </div>
    @empty
    <div style="text-align:center;padding:3rem;color:var(--text-muted)">
        <div style="font-size:42px;margin-bottom:.75rem">📅</div>
        <div style="font-size:.95rem;font-weight:600;color:var(--text);margin-bottom:.35rem">
            No appointments yet
        </div>
        <div style="font-size:.875rem">This doctor has no appointment history.</div>
    </div>
    @endforelse
</div>

@endsection