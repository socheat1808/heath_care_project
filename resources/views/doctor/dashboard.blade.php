@extends('doctor.layout')
@section('title', 'Dashboard')
@section('content')

@php
$user = Auth::user();
$doctor = \App\Models\Doctor::where('email', $user->email)->first();
$hour = now()->hour;
$greet = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening' );
    $newPending=$doctor
    ? \App\Models\Appointment::where('doctor_id', $doctor->DoctorID)
    ->where('status', 'pending')
    ->whereDate('created_at', today())
    ->count()
    : 0;
    @endphp

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div>
            <h1>{{ $greet }}, Dr. {{ $doctor->first_name ?? $user->name }} 👋</h1>
            <p>Here's your practice overview for today, {{ now()->format('l, d M Y') }}.</p>
        </div>
        <a href="{{ route('doctor.appointments.index') }}" class="btn-primary">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            View Appointments
        </a>
    </div>

    {{-- ── Pending Alert ── --}}
    @if($newPending > 0)
    <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;
            padding:1rem 1.25rem;margin-bottom:1.25rem;
            display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap">
        <div style="display:flex;align-items:center;gap:.75rem">
            <div style="font-size:1.5rem">🔔</div>
            <div>
                <div style="font-size:.9rem;font-weight:700;color:#92400e">
                    {{ $newPending }} new appointment{{ $newPending > 1 ? 's' : '' }} today
                </div>
                <div style="font-size:.8rem;color:#b45309">
                    Patients are waiting — please review
                </div>
            </div>
        </div>
        <a href="{{ route('doctor.appointments.index') }}"
            class="btn-primary"
            style="font-size:.83rem;padding:.45rem 1rem;text-decoration:none">
            Review Now →
        </a>
    </div>
    @endif

    {{-- ── Flash ── --}}
    @if(session('success'))
    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:.85rem 1.1rem;
            margin-bottom:1.25rem;font-size:.875rem;color:#15803d;display:flex;align-items:center;gap:.65rem">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;flex-shrink:0">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- rest of content... --}}

    {{-- ── Stat Cards ── --}}
    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon green">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="stat-trend up">Today</span>
            </div>
            <div class="stat-value">{{ $todayAppointments }}</div>
            <div class="stat-label">Today's Appointments</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon blue">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <span class="stat-trend up">Total</span>
            </div>
            <div class="stat-value">{{ $totalPatients }}</div>
            <div class="stat-label">Total Patients</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon amber">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="stat-trend down">Pending</span>
            </div>
            <div class="stat-value">{{ $pendingAppointments }}</div>
            <div class="stat-label">Pending Appointments</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon purple">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="stat-trend up">This month</span>
            </div>
            <div class="stat-value">{{ $completedThisMonth }}</div>
            <div class="stat-label">Completed This Month</div>
        </div>

    </div>

    {{-- ── Bottom Row ── --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem">

        {{-- Today's Appointments --}}
        <div class="chart-card">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem">
                <div>
                    <div class="card-title">Today's Appointments</div>
                    <div class="card-subtitle">{{ now()->format('l, d M Y') }}</div>
                </div>
                <span class="badge badge-green">{{ $todayAppointments }} scheduled</span>
            </div>

            @forelse($todayList as $appt)
            <div style="display:flex;align-items:center;gap:.85rem;padding:.75rem 0;border-bottom:1px solid var(--border)">

                {{-- Time --}}
                <div style="font-size:.8rem;font-weight:600;color:var(--text-muted);width:44px;flex-shrink:0">
                    {{ \Carbon\Carbon::parse($appt->appointment_time)->format('H:i') }}
                </div>

                {{-- Dot --}}
                <div style="width:8px;height:8px;border-radius:50%;flex-shrink:0;
                        background:{{ $appt->status === 'approved' ? 'var(--green)' : ($appt->status === 'pending' ? '#f59e0b' : '#ef4444') }}">
                </div>

                {{-- Patient info --}}
                <div style="flex:1;min-width:0">
                    <div style="font-size:.875rem;font-weight:600;color:var(--text)">
                        {{ $appt->patient->name ?? 'Patient' }}
                    </div>
                    <div style="font-size:.78rem;color:var(--text-muted)">
                        {{ $appt->department ?? 'General' }}
                        @if($appt->visit_type)
                        · {{ ucfirst(str_replace('-',' ',$appt->visit_type)) }}
                        @endif
                    </div>
                </div>

                {{-- Status badge --}}
                @php
                $bc = match($appt->status) {
                'approved' => 'badge-green',
                'pending' => 'badge-amber',
                'completed' => 'badge-blue',
                default => 'badge-red',
                };
                @endphp
                <span class="badge {{ $bc }}">{{ ucfirst($appt->status) }}</span>

                {{-- Mark complete button --}}
                @if($appt->status === 'approved')
                <form method="POST"
                    action="{{ route('doctor.appointments.complete', $appt->id) }}">
                    @csrf @method('PATCH')
                    <button type="submit" title="Mark complete"
                        style="display:inline-flex;align-items:center;justify-content:center;
                               width:28px;height:28px;border-radius:7px;background:var(--bg);
                               border:1px solid var(--border);color:var(--text-muted);cursor:pointer"
                        onmouseover="this.style.borderColor='var(--green)';this.style.color='var(--green)'"
                        onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:13px;height:13px">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                </form>
                @endif

            </div>
            @empty
            <div style="text-align:center;padding:2rem;color:var(--text-muted);font-size:.875rem">
                <div style="font-size:36px;margin-bottom:.5rem">📅</div>
                No appointments scheduled for today.
            </div>
            @endforelse

            <a href="{{ route('doctor.appointments.index') }}"
                style="display:block;text-align:center;margin-top:1rem;font-size:.85rem;
                  color:var(--green);font-weight:600;text-decoration:none">
                View all appointments →
            </a>
        </div>

        {{-- Doctor Profile Summary --}}
        <div class="chart-card">
            <div style="margin-bottom:1.25rem">
                <div class="card-title">My Profile</div>
                <div class="card-subtitle">Your professional summary</div>
            </div>

            {{-- Avatar + name --}}
            <div style="display:flex;align-items:center;gap:1rem;padding:1rem;
                    background:var(--bg);border-radius:12px;margin-bottom:1.25rem">
                <div style="width:56px;height:56px;border-radius:50%;background:var(--green);color:#fff;
                        display:flex;align-items:center;justify-content:center;
                        font-size:1.25rem;font-weight:700;flex-shrink:0">
                    {{ strtoupper(substr($doctor->first_name ?? 'D',0,1).substr($doctor->last_name ?? 'R',0,1)) }}
                </div>
                <div>
                    <div style="font-size:1rem;font-weight:700;color:var(--text)">
                        Dr. {{ $doctor->first_name ?? '' }} {{ $doctor->last_name ?? $user->name }}
                    </div>
                    <div style="font-size:.8rem;color:var(--text-muted)">{{ $doctor->specialization ?? 'Doctor' }}</div>
                    @if($doctor && $doctor->status === 'available')
                    <span class="badge badge-green" style="margin-top:.3rem">● Available</span>
                    @elseif($doctor && $doctor->status === 'onleave')
                    <span class="badge badge-amber" style="margin-top:.3rem">● On Leave</span>
                    @else
                    <span class="badge badge-red" style="margin-top:.3rem">● Unavailable</span>
                    @endif
                </div>
            </div>

            {{-- Info rows --}}
            @if($doctor)
            <div style="display:flex;flex-direction:column;gap:.6rem">
                <div style="display:flex;justify-content:space-between;font-size:.875rem;padding:.5rem 0;border-bottom:1px solid var(--border)">
                    <span style="color:var(--text-muted)">Experience</span>
                    <span style="font-weight:600;color:var(--text)">{{ $doctor->years_of_experience }} years</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:.875rem;padding:.5rem 0;border-bottom:1px solid var(--border)">
                    <span style="color:var(--text-muted)">Consultation Fee</span>
                    <span style="font-weight:600;color:var(--text)">${{ number_format($doctor->consultation_fee, 0) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:.875rem;padding:.5rem 0;border-bottom:1px solid var(--border)">
                    <span style="color:var(--text-muted)">Email</span>
                    <span style="font-weight:600;color:var(--text)">{{ $user->email }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:.875rem;padding:.5rem 0;border-bottom:1px solid var(--border)">
                    <span style="color:var(--text-muted)">Phone</span>
                    <span style="font-weight:600;color:var(--text)">{{ $doctor->phone ?? '—' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:.875rem;padding:.5rem 0">
                    <span style="color:var(--text-muted)">Schedule</span>
                    <a href="{{ route('doctor.schedule.index') }}"
                        style="font-weight:600;color:var(--green);text-decoration:none;font-size:.875rem">
                        View schedule →
                    </a>
                </div>
            </div>
            @endif

            {{-- Action buttons --}}
            <div style="display:flex;gap:.65rem;margin-top:1.25rem">
                <a href="{{ route('doctor.profile.edit') }}"
                    class="btn-primary"
                    style="flex:1;justify-content:center;text-decoration:none">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:15px;height:15px">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Profile
                </a>
                <a href="{{ route('doctor.appointments.create') }}"
                    style="flex:1;display:inline-flex;align-items:center;justify-content:center;gap:.45rem;
                      padding:.55rem 1rem;border-radius:10px;border:1px solid var(--border);
                      color:var(--text-muted);text-decoration:none;font-size:.875rem;font-weight:600;
                      background:var(--bg)">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:15px;height:15px">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    New Appointment
                </a>
            </div>
        </div>

    </div>

    @endsection