@extends('doctor.layout')
@section('title', 'My Schedule')
@section('content')

@php
$user = Auth::user();
$doctor = \App\Models\Doctor::where('email', $user->email)->first();
$days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
@endphp

{{-- ── Page Header ────────────────────────────────────────── --}}
<div class="page-header">
    <div>
        <h1>My Schedule</h1>
        <p>Manage your weekly availability and working hours.</p>
    </div>
    <div style="display:flex;gap:.65rem">
        <a href="{{ route('doctor.appointments.index') }}"
            style="display:inline-flex;align-items:center;gap:.45rem;padding:.55rem 1.1rem;
                  border:1px solid var(--border);border-radius:10px;color:var(--text-muted);
                  text-decoration:none;font-size:.85rem;font-weight:600;background:var(--bg)">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:15px;height:15px">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Appointments
        </a>
        <button onclick="document.getElementById('schedule-form').submit()" class="btn-primary">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:15px;height:15px">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            Save Schedule
        </button>
    </div>
</div>

{{-- ── Success / Error flash ───────────────────────────────── --}}
@if(session('success'))
<div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:.85rem 1.1rem;
            margin-bottom:1.25rem;display:flex;align-items:center;gap:.65rem;font-size:.875rem;color:#15803d">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;flex-shrink:0">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:.85rem 1.1rem;
            margin-bottom:1.25rem;display:flex;gap:.65rem;font-size:.875rem;color:#b91c1c">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;flex-shrink:0;margin-top:.1rem">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <div>{{ $errors->first() }}</div>
</div>
@endif

{{-- ── Stat Cards ─────────────────────────────────────────── --}}
<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon green">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <span class="stat-trend up">Week</span>
        </div>
        <div class="stat-value">{{ $activeDays }}</div>
        <div class="stat-label">Active Days</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon blue">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="stat-trend up">Total</span>
        </div>
        <div class="stat-value">{{ $totalHours }}h</div>
        <div class="stat-label">Weekly Hours</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon amber">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <span class="stat-trend up">Today</span>
        </div>
        <div class="stat-value">{{ $todayAppointments }}</div>
        <div class="stat-label">Today's Appointments</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon purple">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="stat-trend {{ optional($doctor)->status === 'available' ? 'up' : 'down' }}">
                Status
            </span>
        </div>
        <div class="stat-value" style="font-size:1.1rem;padding-top:.15rem">
            @if(optional($doctor)->status === 'available') Available
            @elseif(optional($doctor)->status === 'onleave') On Leave
            @else Unavailable
            @endif
        </div>
        <div class="stat-label">Current Status</div>
    </div>

</div>

{{-- ── Weekly Schedule Form ────────────────────────────────── --}}
<form id="schedule-form" method="POST" action="{{ route('doctor.schedule.update') }}">
    @csrf @method('PUT')

    <div class="chart-card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem">
            <div>
                <div class="card-title">Weekly Availability</div>
                <div class="card-subtitle">Toggle each day and set your working hours</div>
            </div>
            {{-- Quick status toggle --}}
            <div style="display:flex;align-items:center;gap:.65rem">
                <span style="font-size:.83rem;color:var(--text-muted);font-weight:600">Overall status:</span>
                <select name="doctor_status"
                    style="padding:.45rem .85rem;border:1px solid var(--border);border-radius:8px;
                           background:var(--bg);color:var(--text);font-size:.83rem;outline:none">
                    <option value="available" {{ optional($doctor)->status === 'available'   ? 'selected' : '' }}>Available</option>
                    <option value="unavailable" {{ optional($doctor)->status === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                    <option value="onleave" {{ optional($doctor)->status === 'onleave'     ? 'selected' : '' }}>On Leave</option>
                </select>
            </div>
        </div>

        {{-- Day rows --}}
        @foreach($days as $day)
        @php
        $schedule = $schedules->firstWhere('day', $day);
        $isActive = $schedule ? (bool)$schedule->is_active : false;
        $from = $schedule->start_time ?? '09:00';
        $to = $schedule->end_time ?? '17:00';
        $maxSlots = $schedule->max_appointments ?? 10;
        @endphp

        <div class="schedule-row" id="row-{{ $day }}"
            style="display:grid;grid-template-columns:160px 1fr;gap:1rem;
                    padding:1rem 0;border-bottom:1px solid var(--border);align-items:center">

            {{-- Day toggle --}}
            <div style="display:flex;align-items:center;gap:.75rem">
                <label class="toggle-switch">
                    <input type="checkbox" name="schedule[{{ $day }}][is_active]" value="1"
                        {{ $isActive ? 'checked' : '' }}
                        onchange="toggleDay('{{ $day }}', this.checked)">
                    <span class="toggle-track"></span>
                </label>
                <div>
                    <div style="font-size:.9rem;font-weight:600;color:var(--text)">{{ $day }}</div>
                    <div style="font-size:.75rem;color:var(--text-muted)" id="label-{{ $day }}">
                        {{ $isActive ? $from.' – '.$to : 'Day off' }}
                    </div>
                </div>
            </div>

            {{-- Time inputs (hidden when inactive) --}}
            <div id="inputs-{{ $day }}"
                style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;{{ !$isActive ? 'opacity:.35;pointer-events:none;' : '' }}">

                {{-- Start time --}}
                <div style="display:flex;align-items:center;gap:.5rem">
                    <span style="font-size:.8rem;color:var(--text-muted);white-space:nowrap">From</span>
                    <input type="time"
                        name="schedule[{{ $day }}][start_time]"
                        value="{{ $schedule && $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '09:00' }}"
                        style="padding:.45rem .7rem;border:1px solid var(--border);border-radius:8px;
                   background:var(--bg);color:var(--text);font-size:.83rem;outline:none"
                        onfocus="this.style.borderColor='var(--green)'"
                        onblur="this.style.borderColor='var(--border)'"
                        onchange="updateLabel('{{ $day }}')">
                </div>

                {{-- End time --}}
                <div style="display:flex;align-items:center;gap:.5rem">
                    <span style="font-size:.8rem;color:var(--text-muted);white-space:nowrap">To</span>
                    <input type="time"
                        name="schedule[{{ $day }}][end_time]"
                        value="{{ $schedule && $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '17:00' }}"
                        style="padding:.45rem .7rem;border:1px solid var(--border);border-radius:8px;
                   background:var(--bg);color:var(--text);font-size:.83rem;outline:none"
                        onfocus="this.style.borderColor='var(--green)'"
                        onblur="this.style.borderColor='var(--border)'"
                        onchange="updateLabel('{{ $day }}')">
                </div>

                {{-- Max slots --}}
                <div style="display:flex;align-items:center;gap:.5rem">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                        style="width:14px;height:14px;color:var(--text-muted)">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <input type="number"
                        name="schedule[{{ $day }}][max_appointments]"
                        value="{{ $schedule->max_appointments ?? 10 }}"
                        min="1" max="50"
                        placeholder="Max slots"
                        style="width:80px;padding:.45rem .7rem;border:1px solid var(--border);border-radius:8px;
                   background:var(--bg);color:var(--text);font-size:.83rem;outline:none"
                        onfocus="this.style.borderColor='var(--green)'"
                        onblur="this.style.borderColor='var(--border)'">
                    <span style="font-size:.8rem;color:var(--text-muted);white-space:nowrap">max slots</span>
                </div>

                {{-- Today badge --}}
                @if(now()->format('l') === $day)
                <span class="badge badge-green" style="margin-left:auto">Today</span>
                @endif

            </div>
        </div>
        @endforeach

        {{-- Submit row --}}
        <div style="display:flex;justify-content:flex-end;gap:.75rem;padding-top:1.25rem">
            <a href="{{ route('doctor.patients.index') }}"
                style="padding:.6rem 1.4rem;border:1px solid var(--border);border-radius:10px;
                      color:var(--text-muted);text-decoration:none;font-size:.875rem;font-weight:600;
                      background:var(--bg)">
                Cancel
            </a>
            <button type="submit" class="btn-primary" style="padding:.6rem 1.6rem;font-size:.875rem">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:15px;height:15px">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Save Schedule
            </button>
        </div>

    </div>
</form>

{{-- ── This Week's Appointments (read-only) ───────────────── --}}
<div class="chart-card" style="margin-top:0">
    <div style="margin-bottom:1.25rem">
        <div class="card-title">This Week's Appointments</div>
        <div class="card-subtitle">{{ now()->startOfWeek()->format('d M') }} – {{ now()->endOfWeek()->format('d M Y') }}</div>
    </div>

    @forelse($weekAppointments as $date => $appts)
    <div style="margin-bottom:1rem">
        <div style="font-size:.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;
                    letter-spacing:.05em;margin-bottom:.5rem;padding-bottom:.35rem;border-bottom:1px solid var(--border)">
            {{ \Carbon\Carbon::parse($date)->format('l, d M') }}
            @if(\Carbon\Carbon::parse($date)->isToday())
            <span class="badge badge-green" style="margin-left:.5rem">Today</span>
            @endif
        </div>
        @foreach($appts as $appt)
        <div style="display:flex;align-items:center;gap:.85rem;padding:.6rem 0">
            <div style="font-size:.8rem;font-weight:600;color:var(--text-muted);width:44px;flex-shrink:0">
                {{ \Carbon\Carbon::parse($appt->appointment_time)->format('H:i') }}
            </div>
            <div style="width:8px;height:8px;border-radius:50%;flex-shrink:0;
                        background:{{ $appt->status==='approved'?'var(--green)':($appt->status==='pending'?'#f59e0b':'#ef4444') }}">
            </div>
            <div style="flex:1">
                <div style="font-size:.875rem;font-weight:600;color:var(--text)">
                    {{ $appt->patient->name ?? 'Patient' }}
                </div>
                <div style="font-size:.76rem;color:var(--text-muted)">{{ $appt->department ?? 'General' }}</div>
            </div>
            @php
            $bc = match($appt->status) {
            'approved' => 'badge-green',
            'pending' => 'badge-amber',
            'completed' => 'badge-blue',
            default => 'badge-red',
            };
            @endphp
            <span class="badge {{ $bc }}">{{ ucfirst($appt->status) }}</span>
        </div>
        @endforeach
    </div>
    @empty
    <div style="text-align:center;padding:2.5rem;color:var(--text-muted);font-size:.875rem">
        <div style="font-size:36px;margin-bottom:.5rem">📅</div>
        No appointments scheduled this week.
    </div>
    @endforelse
</div>

{{-- ── Toggle switch CSS + JS ──────────────────────────────── --}}
<style>
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 40px;
        height: 22px;
        flex-shrink: 0
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0
    }

    .toggle-track {
        position: absolute;
        inset: 0;
        border-radius: 22px;
        cursor: pointer;
        background: var(--border);
        transition: background .2s;
    }

    .toggle-track::before {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        left: 3px;
        top: 3px;
        background: #fff;
        transition: transform .2s;
    }

    .toggle-switch input:checked+.toggle-track {
        background: var(--green)
    }

    .toggle-switch input:checked+.toggle-track::before {
        transform: translateX(18px)
    }
</style>

<script>
    function toggleDay(day, active) {
        const inputs = document.getElementById('inputs-' + day);
        const label = document.getElementById('label-' + day);
        inputs.style.opacity = active ? '1' : '.35';
        inputs.style.pointerEvents = active ? 'auto' : 'none';
        if (!active) label.textContent = 'Day off';
        else updateLabel(day);
    }

    function updateLabel(day) {
        const row = document.getElementById('row-' + day);
        const times = row.querySelectorAll('input[type=time]');
        const label = document.getElementById('label-' + day);
        const cb = row.querySelector('input[type=checkbox]');
        if (cb && cb.checked && times[0] && times[1]) {
            label.textContent = times[0].value + ' – ' + times[1].value;
        }
    }
</script>

@endsection