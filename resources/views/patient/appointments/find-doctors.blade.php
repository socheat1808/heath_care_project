@extends('patient.layout')
@section('title', 'Find a Doctor')
@section('content')

{{-- ── Page Header ── --}}
<div class="page-header">
    <div>
        <h1>Find a Doctor</h1>
        <p>Browse our available doctors and book your appointment.</p>
    </div>
    <a href="{{ route('patient.appointments.find-doctors') }}" class="btn-primary"
        style="display:inline-flex;align-items:center;gap:.45rem;padding:.55rem 1.1rem;
              border:1px solid var(--border);border-radius:10px;color:var(--text-muted);
              text-decoration:none;font-size:.85rem;font-weight:600;background:var(--bg)">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        My Appointments
    </a>
</div>

{{-- ── Search & Filter ── --}}
<div class="chart-card" style="margin-bottom:1.25rem">
    <form method="GET" action="{{ route('patient.appointments.find-doctors') }}"
        style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:center">

        {{-- Search --}}
        <div style="position:relative;flex:1;min-width:200px">
            <svg style="position:absolute;left:.75rem;top:50%;transform:translateY(-50%);
                        width:15px;height:15px;color:var(--text-muted)"
                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z" />
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search by name or specialization…"
                style="width:100%;padding:.6rem .9rem .6rem 2.2rem;border:1px solid var(--border);
                          border-radius:10px;background:var(--bg);color:var(--text);font-size:.875rem;
                          outline:none;box-sizing:border-box"
                onfocus="this.style.borderColor='var(--green)'"
                onblur="this.style.borderColor='var(--border)'">
        </div>

        {{-- Specialization filter --}}
        <select name="specialization"
            style="padding:.6rem .9rem;border:1px solid var(--border);border-radius:10px;
                       background:var(--bg);color:var(--text);font-size:.875rem;outline:none;min-width:180px"
            onfocus="this.style.borderColor='var(--green)'"
            onblur="this.style.borderColor='var(--border)'"
            onchange="this.form.submit()">
            <option value="">All Specializations</option>
            @foreach(['General','Cardiology','Neurology','Orthopedics','Pediatrics','Dermatology','Ophthalmology','ENT','Gynecology','Urology'] as $spec)
            <option value="{{ $spec }}" {{ request('specialization') === $spec ? 'selected' : '' }}>
                {{ $spec }}
            </option>
            @endforeach
        </select>

        {{-- Availability filter --}}
        <select name="status"
            style="padding:.6rem .9rem;border:1px solid var(--border);border-radius:10px;
                       background:var(--bg);color:var(--text);font-size:.875rem;outline:none"
            onfocus="this.style.borderColor='var(--green)'"
            onblur="this.style.borderColor='var(--border)'"
            onchange="this.form.submit()">
            <option value="">All Status</option>
            <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
            <option value="unavailable" {{ request('status') === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
            <option value="onleave" {{ request('status') === 'onleave' ? 'selected' : '' }}>On Leave</option>
        </select>

        <button type="submit" class="btn-primary" style="padding:.6rem 1.2rem">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:15px;height:15px">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z" />
            </svg>
            Search
        </button>

        @if(request()->hasAny(['search','specialization','status']))
        <a href="{{ route('patient.appointments.find-doctors') }}"
            style="padding:.6rem 1rem;border:1px solid var(--border);border-radius:10px;
                  color:var(--text-muted);text-decoration:none;font-size:.875rem;font-weight:600">
            Clear
        </a>
        @endif

    </form>
</div>

{{-- ── Results count ── --}}
<div style="font-size:.83rem;color:var(--text-muted);margin-bottom:1rem">
    Showing <strong style="color:var(--text)">{{ $doctors->total() }}</strong> doctors found
    @if(request('specialization'))
    in <strong style="color:var(--text)">{{ request('specialization') }}</strong>
    @endif
</div>

{{-- ── Doctor Cards Grid ── --}}
@forelse($doctors as $doctor)
@php
$isAvailable = $doctor->status === 'available';
$activeDays = optional($doctor->schedules)->where('is_active', true)->count() ?? 0;
@endphp

<div style="border:1px solid var(--border);border-radius:14px;padding:1.25rem;
            margin-bottom:1rem;background:var(--bg);transition:border-color .2s;display:flex;
            align-items:center;gap:1.25rem;flex-wrap:wrap"
    onmouseover="this.style.borderColor='var(--green)'"
    onmouseout="this.style.borderColor='var(--border)'">

    {{-- Avatar --}}
    <div style="width:64px;height:64px;border-radius:50%;background:{{ $isAvailable ? 'var(--green)' : '#9ca3af' }};
                color:#fff;display:flex;align-items:center;justify-content:center;
                font-size:1.3rem;font-weight:700;flex-shrink:0">
        {{ strtoupper(substr($doctor->first_name,0,1).substr($doctor->last_name,0,1)) }}
    </div>

    {{-- Info --}}
    <div style="flex:1;min-width:0">
        <div style="display:flex;align-items:center;gap:.65rem;flex-wrap:wrap;margin-bottom:.25rem">
            <div style="font-size:1rem;font-weight:700;color:var(--text)">
                Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
            </div>
            {{-- Status badge --}}
            @if($doctor->status === 'available')
            <span class="badge badge-green">● Available</span>
            @elseif($doctor->status === 'onleave')
            <span class="badge badge-amber">● On Leave</span>
            @else
            <span class="badge badge-red">● Unavailable</span>
            @endif
        </div>

        <div style="font-size:.83rem;color:var(--text-muted);margin-bottom:.65rem">
            {{ $doctor->specialization }}
        </div>

        {{-- Detail pills --}}
        <div style="display:flex;gap:.75rem;flex-wrap:wrap;font-size:.8rem;color:var(--text-muted)">

            <div style="display:flex;align-items:center;gap:.3rem">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:13px;height:13px">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ $doctor->years_of_experience ?? 0 }} yrs experience
            </div>

            <div style="display:flex;align-items:center;gap:.3rem">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:13px;height:13px">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                ${{ number_format($doctor->consultation_fee, 0) }} / visit
            </div>

            @if($activeDays > 0)
            <div style="display:flex;align-items:center;gap:.3rem">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:13px;height:13px">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ $activeDays }} days/week
            </div>
            @endif

            @if($doctor->biography_note)
            <div style="width:100%;margin-top:.25rem;font-size:.8rem;color:var(--text-muted);
                        overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:480px">
                {{ $doctor->biography_note }}
            </div>
            @endif

        </div>
    </div>

    {{-- Actions --}}
    <div style="display:flex;flex-direction:column;gap:.5rem;flex-shrink:0;min-width:130px">

        @if($isAvailable)
        <a href="{{ route('patient.appointments.book', $doctor->DoctorID) }}"
            class="btn-primary" style="justify-content:center;text-decoration:none">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:14px;height:14px">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Book Now
        </a>
        @else
        <button disabled
            style="display:inline-flex;align-items:center;justify-content:center;gap:.4rem;
                       padding:.55rem 1rem;border-radius:10px;background:#f3f4f6;color:#9ca3af;
                       border:1px solid var(--border);font-size:.875rem;font-weight:600;cursor:not-allowed">
            Not Available
        </button>
        @endif

        <a href="{{ route('patient.appointments.doctor-profile', $doctor->DoctorID) }}"
            style="display:inline-flex;align-items:center;justify-content:center;gap:.4rem;
                  padding:.5rem 1rem;border-radius:10px;border:1px solid var(--border);
                  color:var(--text-muted);text-decoration:none;font-size:.83rem;font-weight:600;
                  background:var(--bg)"
            onmouseover="this.style.borderColor='var(--green)';this.style.color='var(--green)'"
            onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">
            View Profile
        </a>
    </div>

</div>

@empty
<div style="text-align:center;padding:4rem;border:1px solid var(--border);
            border-radius:14px;background:var(--bg)">
    <div style="font-size:48px;margin-bottom:.75rem">🔍</div>
    <div style="font-size:1rem;font-weight:600;color:var(--text);margin-bottom:.35rem">No doctors found</div>
    <div style="font-size:.875rem;color:var(--text-muted);margin-bottom:1.25rem">
        Try adjusting your search or filters.
    </div>
    <a href="{{ route('home') }}" class="btn-primary" style="text-decoration:none">
        Clear filters
    </a>
</div>
@endforelse

{{-- ── Pagination ── --}}
@if($doctors->hasPages())
<div style="display:flex;align-items:center;justify-content:space-between;
            margin-top:1.25rem;padding-top:1rem;border-top:1px solid var(--border);font-size:.83rem">
    <div style="color:var(--text-muted)">
        Showing {{ $doctors->firstItem() }}–{{ $doctors->lastItem() }} of {{ $doctors->total() }}
    </div>
    <div style="display:flex;gap:.35rem">
        @if(!$doctors->onFirstPage())
        <a href="{{ $doctors->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
            style="padding:.4rem .75rem;border:1px solid var(--border);border-radius:7px;
                  color:var(--text);text-decoration:none">← Prev</a>
        @endif
        @foreach($doctors->getUrlRange(max(1,$doctors->currentPage()-2), min($doctors->lastPage(),$doctors->currentPage()+2)) as $page => $url)
        <a href="{{ $url }}&{{ http_build_query(request()->except('page')) }}"
            style="padding:.4rem .75rem;border:1px solid {{ $page == $doctors->currentPage() ? 'var(--green)' : 'var(--border)' }};
                  border-radius:7px;color:{{ $page == $doctors->currentPage() ? 'var(--green)' : 'var(--text)' }};
                  font-weight:{{ $page == $doctors->currentPage() ? '700' : '400' }};text-decoration:none">
            {{ $page }}
        </a>
        @endforeach
        @if($doctors->hasMorePages())
        <a href="{{ $doctors->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
            style="padding:.4rem .75rem;border:1px solid var(--border);border-radius:7px;
                  color:var(--text);text-decoration:none">Next →</a>
        @endif
    </div>
</div>
@endif

@endsection