{{-- resources/views/patient/my-appointments.blade.php --}}
@extends('patient.layout')
@section('title', 'My Appointments')
@section('content')

{{-- ── Page Header ── --}}
<div class="page-header">
    <div>
        <h1>My Appointments</h1>
        <p>Track all your bookings and their current status.</p>
    </div>
    <a href="{{ route('patient.appointments.index') }}" class="btn-primary">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Book New
    </a>
</div>

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

{{-- ── Stat Cards ── --}}
<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon green">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <span class="stat-trend up">All</span>
        </div>
        <div class="stat-value">{{ $allAppointments->count() }}</div>
        <div class="stat-label">Total Appointments</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon amber">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="stat-trend down">Waiting</span>
        </div>
        <div class="stat-value">{{ $allAppointments->where('status','pending')->count() }}</div>
        <div class="stat-label">Pending</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon blue">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="stat-trend up">Approved</span>
        </div>
        <div class="stat-value">{{ $allAppointments->where('status','approved')->count() }}</div>
        <div class="stat-label">Upcoming</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon purple">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="stat-trend up">Done</span>
        </div>
        <div class="stat-value">{{ $allAppointments->where('status','completed')->count() }}</div>
        <div class="stat-label">Completed</div>
    </div>

</div>

{{-- ── Filter pills + list ── --}}
<div class="chart-card">

    {{-- Header + pills --}}
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:1.25rem">
        <div>
            <div class="card-title">All Appointments</div>
            <div class="card-subtitle">{{ $appointments->total() }} records found</div>
        </div>

        <div style="display:flex;gap:.5rem;flex-wrap:wrap">
            @foreach([
            '' => 'All',
            'pending' => 'Pending',
            'approved' => 'Approved',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            ] as $val => $label)
            <a href="#"
                style="padding:.35rem .9rem;border-radius:20px;font-size:.78rem;font-weight:600;
                      text-decoration:none;border:1px solid var(--border);transition:all .15s;
                      background:{{ request('status',$val===''?'':request('status')) === $val ? 'var(--green)' : 'transparent' }};
                      color:{{ request('status', '') === $val ? '#fff' : 'var(--text-muted)' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- Appointment rows --}}
    @forelse($appointments as $appt)
    @php
    $statusConfig = match($appt->status) {
    'approved' => ['Approved', 'badge-green'],
    'pending' => ['Pending', 'badge-amber'],
    'completed' => ['Completed', 'badge-blue'],
    'cancelled' => ['Cancelled', 'badge-red'],
    'rejected' => ['Rejected', 'badge-red'],
    default => ['Unknown', 'badge-amber'],
    };
    @endphp

    <div style="border:1px solid var(--border);border-radius:12px;padding:1rem 1.25rem;
                margin-bottom:.85rem;transition:border-color .15s"
        onmouseover="this.style.borderColor='var(--green)'"
        onmouseout="this.style.borderColor='var(--border)'">

        <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:.75rem">

            {{-- Doctor info --}}
            <div style="display:flex;align-items:center;gap:.85rem">
                <div style="width:44px;height:44px;border-radius:50%;background:var(--green);color:#fff;
                            display:flex;align-items:center;justify-content:center;font-size:.9rem;
                            font-weight:700;flex-shrink:0">
                    {{ strtoupper(substr($appt->doctor->first_name ?? 'D',0,1).substr($appt->doctor->last_name ?? 'R',0,1)) }}
                </div>
                <div>
                    <div style="font-size:.95rem;font-weight:700;color:var(--text)">
                        Dr. {{ $appt->doctor->first_name ?? '' }} {{ $appt->doctor->last_name ?? '—' }}
                    </div>
                    <div style="font-size:.78rem;color:var(--text-muted)">
                        {{ $appt->doctor->specialization ?? 'General' }}
                    </div>
                </div>
            </div>

            <span class="badge {{ $statusConfig[1] }}">{{ $statusConfig[0] }}</span>
        </div>

        @if($appt->status === 'rejected' && $appt->rejection_reason)
        <div style="margin-top:.75rem;padding:.75rem 1rem;
                background:#fef2f2;border:1px solid #fecaca;border-radius:8px;
                display:flex;align-items:flex-start;gap:.65rem;font-size:.83rem">
            <svg fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="2"
                style="width:15px;height:15px;flex-shrink:0;margin-top:.1rem">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <div style="font-weight:700;color:#b91c1c;margin-bottom:.2rem">
                    Appointment Rejected
                </div>
                <div style="color:#dc2626">
                    <span style="font-weight:600">Reason:</span> {{ $appt->rejection_reason }}
                </div>
            </div>
        </div>
        @endif
        {{-- Details row --}}
        <div style="display:flex;gap:1.5rem;flex-wrap:wrap;margin-top:.85rem;
                    padding-top:.85rem;border-top:1px solid var(--border)">

            <div style="display:flex;align-items:center;gap:.4rem;font-size:.83rem;color:var(--text-muted)">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span style="color:var(--text);font-weight:600">
                    {{ \Carbon\Carbon::parse($appt->appointment_date)->format('d M Y') }}
                </span>
            </div>

            <div style="display:flex;align-items:center;gap:.4rem;font-size:.83rem;color:var(--text-muted)">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span style="color:var(--text);font-weight:600">
                    {{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}
                </span>
            </div>

            <div style="display:flex;align-items:center;gap:.4rem;font-size:.83rem;color:var(--text-muted)">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
                </svg>
                {{ $appt->department ?? 'General' }}
            </div>

            @if($appt->visit_type)
            <div style="font-size:.83rem;color:var(--text-muted)">
                {{ ucfirst(str_replace('-',' ', $appt->visit_type)) }}
            </div>
            @endif
        </div>

        {{-- Notes --}}
        @if($appt->notes)
        <div style="margin-top:.75rem;padding:.7rem 1rem;background:var(--bg);
                    border:1px solid var(--border);border-radius:8px;font-size:.82rem;color:var(--text-muted)">
            <span style="font-weight:600;color:var(--text)">Notes:</span> {{ $appt->notes }}
        </div>
        @endif

        {{-- Cancel button --}}
        @if($appt->status === 'pending')
        <div style="margin-top:.85rem;display:flex;justify-content:flex-end">
            <form method="POST" action="{{ route('patient.appointments.cancel', $appt->id) }}"
                onsubmit="return confirm('Cancel this appointment?')">
                @csrf @method('PATCH')
                <button type="submit"
                    style="padding:.4rem 1rem;border:1px solid #fecaca;border-radius:8px;
                               background:#fef2f2;color:#b91c1c;font-size:.8rem;font-weight:600;cursor:pointer">
                    Cancel appointment
                </button>
            </form>
        </div>
        @endif

    </div>
    @empty
    <div style="text-align:center;padding:3rem;color:var(--text-muted)">
        <div style="font-size:42px;margin-bottom:.75rem">📅</div>
        <div style="font-size:1rem;font-weight:600;color:var(--text);margin-bottom:.35rem">No appointments found</div>
        <div style="font-size:.875rem;margin-bottom:1.25rem">Book your first appointment with a doctor.</div>
        <a href="{{ route('patient.doctors.index') }}" class="btn-primary" style="text-decoration:none">Browse Doctors</a>
    </div>
    @endforelse

    {{-- Pagination --}}
    @if($appointments->hasPages())
    <div style="display:flex;align-items:center;justify-content:space-between;
                margin-top:1.25rem;padding-top:1rem;border-top:1px solid var(--border);font-size:.83rem">
        <div style="color:var(--text-muted)">
            Showing {{ $appointments->firstItem() }}–{{ $appointments->lastItem() }} of {{ $appointments->total() }}
        </div>
        <div style="display:flex;gap:.35rem">
            @if(!$appointments->onFirstPage())
            <a href="{{ $appointments->previousPageUrl() }}"
                style="padding:.4rem .75rem;border:1px solid var(--border);border-radius:7px;
                      color:var(--text);text-decoration:none">← Prev</a>
            @endif
            @foreach($appointments->getUrlRange(max(1,$appointments->currentPage()-2), min($appointments->lastPage(),$appointments->currentPage()+2)) as $page => $url)
            <a href="{{ $url }}"
                style="padding:.4rem .75rem;border:1px solid {{ $page == $appointments->currentPage() ? 'var(--green)' : 'var(--border)' }};
                      border-radius:7px;color:{{ $page == $appointments->currentPage() ? 'var(--green)' : 'var(--text)' }};
                      font-weight:{{ $page == $appointments->currentPage() ? '700' : '400' }};text-decoration:none">
                {{ $page }}
            </a>
            @endforeach
            @if($appointments->hasMorePages())
            <a href="{{ $appointments->nextPageUrl() }}"
                style="padding:.4rem .75rem;border:1px solid var(--border);border-radius:7px;
                      color:var(--text);text-decoration:none">Next →</a>
            @endif
        </div>
    </div>
    @endif

</div>

@endsection