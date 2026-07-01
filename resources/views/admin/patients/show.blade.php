@extends('admin.layout')
@section('title', 'Patient History')
@section('content')

{{-- ── Page Header ── --}}
<div class="page-header">
    <div class="page-header-left">
        <h1>{{ $patient->name }}</h1>
        <p>Full appointment history and patient details.</p>
    </div>
    <a href="{{ route('admin.patients.index') }}"
        style="display:inline-flex;align-items:center;gap:.45rem;padding:.55rem 1.1rem;
               border:1px solid var(--border);border-radius:10px;color:var(--text-muted);
               text-decoration:none;font-size:.85rem;font-weight:600;background:var(--bg)">
        ← Back to Patients
    </a>
</div>

{{-- ── Patient Info Card ── --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem">

    <div class="chart-card" style="margin-bottom:0">
        <div class="card-title" style="margin-bottom:1rem">Patient Information</div>
        <div style="display:flex;align-items:center;gap:1rem;padding:1rem;
                    background:var(--bg);border-radius:12px;margin-bottom:1.25rem">
            <div style="width:56px;height:56px;border-radius:50%;background:var(--green);color:#fff;
                        display:flex;align-items:center;justify-content:center;
                        font-size:1.25rem;font-weight:700;flex-shrink:0">
                {{ strtoupper(substr($patient->name, 0, 2)) }}
            </div>
            <div>
                <div style="font-size:1rem;font-weight:700;color:var(--text)">{{ $patient->name }}</div>
                <div style="font-size:.8rem;color:var(--text-muted)">{{ $patient->email }}</div>
                <span class="badge {{ $patient->status === 'approved' ? 'badge-green' : 'badge-red' }}"
                    style="margin-top:.3rem">
                    {{ $patient->status === 'approved' ? 'Active' : ucfirst($patient->status) }}
                </span>
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:.5rem">
            <div style="display:flex;justify-content:space-between;font-size:.875rem;
                        padding:.5rem 0;border-bottom:1px solid var(--border)">
                <span style="color:var(--text-muted)">Phone</span>
                <span style="font-weight:600;color:var(--text)">{{ $patient->phone ?? '—' }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:.875rem;
                        padding:.5rem 0;border-bottom:1px solid var(--border)">
                <span style="color:var(--text-muted)">Email</span>
                <span style="font-weight:600;color:var(--text)">{{ $patient->email }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:.875rem;
                        padding:.5rem 0;border-bottom:1px solid var(--border)">
                <span style="color:var(--text-muted)">Joined</span>
                <span style="font-weight:600;color:var(--text)">
                    {{ $patient->created_at->format('d M Y') }}
                </span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:.875rem;padding:.5rem 0">
                <span style="color:var(--text-muted)">Role</span>
                <span class="badge badge-blue">Patient</span>
            </div>
        </div>
    </div>

    {{-- ── Stats ── --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">

        <div class="chart-card" style="margin-bottom:0;text-align:center">
            <div class="stat-icon green" style="margin:0 auto .75rem">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div style="font-size:1.75rem;font-weight:700;color:var(--text)">{{ $totalAppointments }}</div>
            <div style="font-size:.8rem;color:var(--text-muted);margin-top:.25rem">Total Appointments</div>
        </div>

        <div class="chart-card" style="margin-bottom:0;text-align:center">
            <div class="stat-icon amber" style="margin:0 auto .75rem">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div style="font-size:1.75rem;font-weight:700;color:var(--text)">{{ $pendingAppointments }}</div>
            <div style="font-size:.8rem;color:var(--text-muted);margin-top:.25rem">Pending</div>
        </div>

        <div class="chart-card" style="margin-bottom:0;text-align:center">
            <div class="stat-icon blue" style="margin:0 auto .75rem">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div style="font-size:1.75rem;font-weight:700;color:var(--text)">{{ $completedAppointments }}</div>
            <div style="font-size:.8rem;color:var(--text-muted);margin-top:.25rem">Completed</div>
        </div>

        <div class="chart-card" style="margin-bottom:0;text-align:center">
            <div class="stat-icon red" style="margin:0 auto .75rem">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <div style="font-size:1.75rem;font-weight:700;color:var(--text)">{{ $cancelledAppointments }}</div>
            <div style="font-size:.8rem;color:var(--text-muted);margin-top:.25rem">Cancelled</div>
        </div>

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

        <div style="display:flex;align-items:flex-start;justify-content:space-between;
                    gap:.75rem;flex-wrap:wrap">

            {{-- Doctor info --}}
            <div style="display:flex;align-items:center;gap:.85rem">
                <div style="width:42px;height:42px;border-radius:50%;background:var(--green);color:#fff;
                            display:flex;align-items:center;justify-content:center;
                            font-size:.85rem;font-weight:700;flex-shrink:0">
                    {{ strtoupper(substr($appt->doctor->first_name ?? 'D', 0, 1).substr($appt->doctor->last_name ?? 'R', 0, 1)) }}
                </div>
                <div>
                    <div style="font-size:.9rem;font-weight:700;color:var(--text)">
                        Dr. {{ $appt->doctor->first_name ?? '' }} {{ $appt->doctor->last_name ?? '—' }}
                    </div>
                    <div style="font-size:.78rem;color:var(--text-muted)">
                        {{ $appt->doctor->specialization ?? 'General' }}
                    </div>
                </div>
            </div>

            <span class="badge {{ $badgeClass }}">{{ ucfirst($appt->status) }}</span>
        </div>

        {{-- Details --}}
        <div style="display:flex;gap:1.5rem;flex-wrap:wrap;margin-top:.85rem;
                    padding-top:.85rem;border-top:1px solid var(--border)">

            <div style="display:flex;align-items:center;gap:.4rem;font-size:.83rem;color:var(--text-muted)">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span style="color:var(--text);font-weight:600">
                    {{ \Carbon\Carbon::parse($appt->appointment_date)->format('d M Y') }}
                </span>
            </div>

            <div style="display:flex;align-items:center;gap:.4rem;font-size:.83rem;color:var(--text-muted)">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span style="color:var(--text);font-weight:600">
                    {{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}
                </span>
            </div>

            @if($appt->department)
            <div style="font-size:.83rem;color:var(--text-muted)">
                {{ $appt->department }}
            </div>
            @endif

            @if($appt->visit_type)
            <div style="font-size:.83rem;color:var(--text-muted)">
                {{ ucfirst(str_replace('-', ' ', $appt->visit_type)) }}
            </div>
            @endif
        </div>

        {{-- Rejection reason --}}
        @if($appt->status === 'rejected' && $appt->rejection_reason)
        <div style="margin-top:.75rem;padding:.65rem 1rem;background:#fef2f2;
                    border:1px solid #fecaca;border-radius:8px;font-size:.82rem;color:#b91c1c">
            <span style="font-weight:600">Rejection reason:</span> {{ $appt->rejection_reason }}
        </div>
        @endif

        {{-- Notes --}}
        @if($appt->notes)
        <div style="margin-top:.65rem;padding:.65rem 1rem;background:var(--bg);
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
        <div style="font-size:.875rem">This patient has not booked any appointments.</div>
    </div>
    @endforelse
</div>

@endsection