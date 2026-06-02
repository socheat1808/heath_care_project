@extends('doctor.layout')
@section('title', 'Patient Notes')
@section('content')

<div class="page-header">
    <div>
        <h1>{{ $patient->name }}</h1>
        <p>Medical notes and appointment history with you.</p>
    </div>
    <a href="{{ route('doctor.patients.index') }}"
        style="display:inline-flex;align-items:center;gap:.45rem;padding:.55rem 1.1rem;
               border:1px solid var(--border);border-radius:10px;color:var(--text-muted);
               text-decoration:none;font-size:.85rem;font-weight:600;background:var(--bg)">
        ← Back to Patients
    </a>
</div>

{{-- Patient Info --}}
<div class="chart-card" style="margin-bottom:1.25rem">
    <div style="display:flex;align-items:center;gap:1rem">
        <div style="width:56px;height:56px;border-radius:50%;background:var(--green);color:#fff;
                    display:flex;align-items:center;justify-content:center;font-size:1.2rem;font-weight:700">
            {{ strtoupper(substr($patient->name,0,2)) }}
        </div>
        <div>
            <div style="font-size:1rem;font-weight:700;color:var(--text)">{{ $patient->name }}</div>
            <div style="font-size:.83rem;color:var(--text-muted)">{{ $patient->email }}</div>
            <div style="font-size:.83rem;color:var(--text-muted)">{{ $patient->phone ?? '—' }}</div>
        </div>
        <div style="margin-left:auto;text-align:right">
            <div style="font-size:1.5rem;font-weight:700;color:var(--text)">{{ $appointments->count() }}</div>
            <div style="font-size:.8rem;color:var(--text-muted)">Total visits</div>
        </div>
    </div>
</div>

{{-- Appointment History with Notes --}}
<div class="chart-card">
    <div class="card-title" style="margin-bottom:1.25rem">Appointment History</div>

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
    <div style="border:1px solid var(--border);border-radius:12px;padding:1rem 1.25rem;margin-bottom:.85rem">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem">
            <div>
                <div style="font-size:.9rem;font-weight:700;color:var(--text)">
                    {{ \Carbon\Carbon::parse($appt->appointment_date)->format('d M Y') }}
                    · {{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}
                </div>
                <div style="font-size:.78rem;color:var(--text-muted)">
                    {{ $appt->department ?? 'General' }}
                    @if($appt->visit_type)
                    · {{ ucfirst(str_replace('-',' ',$appt->visit_type)) }}
                    @endif
                </div>
            </div>
            <span class="badge {{ $badgeClass }}">{{ ucfirst($appt->status) }}</span>
        </div>

        {{-- Notes --}}
        @if($appt->notes)
        <div style="background:var(--bg);border-radius:8px;padding:.75rem 1rem;
                    font-size:.83rem;color:var(--text-muted);margin-bottom:.75rem">
            <span style="font-weight:600;color:var(--text)">Notes:</span> {{ $appt->notes }}
        </div>
        @endif

        {{-- Add/Edit notes form --}}
        @if($appt->status === 'completed' || $appt->status === 'approved')
        <form method="POST" action="{{ route('doctor.appointments.notes', $appt->id) }}">
            @csrf @method('PATCH')
            <div style="display:flex;gap:.65rem">
                <input type="text" name="notes"
                    value="{{ $appt->notes }}"
                    placeholder="Add or update notes..."
                    style="flex:1;padding:.5rem .85rem;border:1px solid var(--border);border-radius:8px;
                           background:var(--bg);color:var(--text);font-size:.83rem;outline:none">
                <button type="submit" class="btn-primary" style="padding:.5rem 1rem;font-size:.83rem">
                    Save
                </button>
            </div>
        </form>
        @endif
    </div>
    @empty
    <div style="text-align:center;padding:2rem;color:var(--text-muted)">
        No appointment history found.
    </div>
    @endforelse
</div>

@endsection