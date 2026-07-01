@extends('layouts.main')

@section('content')

{{-- ══════════════════════════════════════
     HERO
══════════════════════════════════════ --}}
<div class="page-hero overlay-dark"
  style="background:linear-gradient(135deg,#0d2137 0%,#1a4a36 100%)">
  <div class="hero-section">
    <div class="container text-center">
      <span class="subhead">Let's make your life happier</span>
      <h1>Healthy Living</h1>
      @auth
      @if(Auth::user()->role === 'patient')
      <a href="{{ route('patient.appointments.find-doctors') }}" class="btn btn-primary">
        Book Appointment
      </a>
      @else
      <a href="#our_doctor" class="btn btn-primary">Explore Doctors</a>
      @endif
      @else
      <a href="{{ route('register') }}" class="btn btn-primary">Get Started Free</a>
      @endauth
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════
     SERVICE CARDS
══════════════════════════════════════ --}}
<div class="bg-light">
  <div class="page-section" style="padding-top:0">
    <div style="margin-top:-3rem;position:relative;z-index:10">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-4 py-3 py-md-0">
            <div class="card-service">
              <div class="circle-shape bg-secondary text-white">💬</div>
              <p><span>Chat</span> with a Doctor</p>
            </div>
          </div>
          <div class="col-md-4 py-3 py-md-0">
            <div class="card-service">
              <div class="circle-shape bg-primary text-white">🛡️</div>
              <p><span>One</span>-Health Protection</p>
            </div>
          </div>
          <div class="col-md-4 py-3 py-md-0">
            <div class="card-service">
              <div class="circle-shape bg-accent text-white">📅</div>
              <p><span>Easy</span> Appointment Booking</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- WELCOME --}}
    <div class="page-section pb-0" id="about_us">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6 py-3">
            <h1>Welcome to Your Health <br>Center</h1>
            <p class="text-grey mb-4" style="margin-top:16px">
              One Health is a modern medical appointment platform connecting
              patients with qualified doctors. Book appointments, manage your
              health, and get the care you need — all in one place.
            </p>
            <a href="#our_doctor" class="btn btn-primary">Meet Our Doctors</a>
          </div>
          <div class="col-lg-6">
            <div class="img-place custom-img-1">
              <svg viewBox="0 0 300 380" width="260" xmlns="http://www.w3.org/2000/svg">
                <circle cx="150" cy="70" r="55" fill="#1A8A6E" opacity=".15" />
                <circle cx="150" cy="65" r="48" fill="#12705A" opacity=".2" />
                <ellipse cx="150" cy="200" rx="70" ry="85" fill="#1A8A6E" opacity=".12" />
                <circle cx="150" cy="60" r="38" fill="#fff" opacity=".8" />
                <text x="150" y="72" font-size="36" text-anchor="middle" font-family="DM Sans,sans-serif">👨‍⚕️</text>
                <rect x="90" y="115" width="120" height="140" rx="16" fill="#1A8A6E" opacity=".18" />
                <rect x="110" y="135" width="80" height="8" rx="4" fill="#1A8A6E" opacity=".4" />
                <rect x="115" y="155" width="70" height="6" rx="3" fill="#1A8A6E" opacity=".3" />
                <rect x="120" y="172" width="60" height="6" rx="3" fill="#1A8A6E" opacity=".2" />
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════
     REAL STATS
══════════════════════════════════════ --}}
<div class="page-section" style="padding:56px 0;background:#fff">
  <div class="container">
    <div class="row text-center">
      <div class="col-lg-3 col-sm-6 py-3">
        <h2 style="color:var(--primary);font-size:2.5rem;font-family:var(--font-body);font-weight:700">
          {{ \App\Models\Doctor::where('status','available')->count() }}+
        </h2>
        <p style="font-weight:500;color:var(--gray-600);margin-top:.25rem">Available Doctors</p>
      </div>
      <div class="col-lg-3 col-sm-6 py-3">
        <h2 style="color:var(--primary);font-size:2.5rem;font-family:var(--font-body);font-weight:700">
          {{ \App\Models\User::where('role','patient')->count() }}+
        </h2>
        <p style="font-weight:500;color:var(--gray-600);margin-top:.25rem">Happy Patients</p>
      </div>
      <div class="col-lg-3 col-sm-6 py-3">
        <h2 style="color:var(--primary);font-size:2.5rem;font-family:var(--font-body);font-weight:700">
          {{ \App\Models\Appointment::where('status','completed')->count() }}+
        </h2>
        <p style="font-weight:500;color:var(--gray-600);margin-top:.25rem">Completed Appointments</p>
      </div>
      <div class="col-lg-3 col-sm-6 py-3">
        <h2 style="color:var(--primary);font-size:2.5rem;font-family:var(--font-body);font-weight:700">10+</h2>
        <p style="font-weight:500;color:var(--gray-600);margin-top:.25rem">Specializations</p>
      </div>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════
     SPECIALIZATIONS
══════════════════════════════════════ --}}
<div class="page-section bg-light" id="specializations">
  <div class="container">
    <h2 class="text-center mb-5">Our Specializations</h2>
    <div class="row justify-content-center">
      @foreach([
      ['🫀', 'Cardiology'],
      ['🧠', 'Neurology'],
      ['🦷', 'Dental'],
      ['👁️', 'Ophthalmology'],
      ['🦴', 'Orthopaedics'],
      ['👶', 'Pediatrics'],
      ['🩺', 'General Health'],
      ['🧬', 'Dermatology'],
      ] as [$icon, $name])
      <div class="col-lg-3 col-sm-6 py-3">
        <div class="card-service" style="flex-direction:column;text-align:center;
                            padding:2rem 1.25rem;justify-content:center">
          <div style="font-size:2.25rem;margin-bottom:.75rem">{{ $icon }}</div>
          <p style="font-size:.9rem;font-weight:600;color:var(--gray-900);margin:0">
            {{ $name }}
          </p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════
     DOCTORS
══════════════════════════════════════ --}}
<div class="page-section" id="our_doctor">
  <div class="container">
    <h2 class="text-center mb-5">Our Doctors</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:24px">

      @php
      $gradients = [
      'linear-gradient(135deg,#E8F7F3 0%,#d1ede6 100%)',
      'linear-gradient(135deg,#EEF2FF 0%,#dde4f7 100%)',
      'linear-gradient(135deg,#FFF5EE 0%,#fce4d6 100%)',
      'linear-gradient(135deg,#F0F9FF 0%,#d6eefa 100%)',
      'linear-gradient(135deg,#FDF4FF 0%,#f0d6fa 100%)',
      'linear-gradient(135deg,#FFFBEB 0%,#faefc6 100%)',
      ];
      @endphp

      @forelse($doctors as $doctor)
      @php
      $bg = $gradients[$loop->index % count($gradients)];
      $initials = strtoupper(substr($doctor->first_name,0,1).substr($doctor->last_name,0,1));
      @endphp

      <div class="card-doctor">
        <div class="header">
          <div style="width:120px;height:120px;border-radius:50%;overflow:hidden;
                                margin:1.5rem auto .75rem;border:3px solid var(--primary)">
            @if(!empty($doctor->photo))
            <img src="{{ asset('storage/'.$doctor->photo) }}"
              style="width:100%;height:100%;object-fit:cover;object-position:top">
            @else
            <div style="width:100%;height:100%;background:{{ $bg }};
                                    display:flex;align-items:center;justify-content:center;
                                    font-size:1.5rem;font-weight:700;color:var(--primary)">
              {{ $initials }}
            </div>
            @endif
          </div>
          <div class="meta">
            <a href="tel:{{ $doctor->phone ?? '#' }}">📞</a>
            <a href="mailto:{{ $doctor->email ?? '#' }}">💬</a>
          </div>
        </div>

        <div class="body">
          <p class="text-xl mb-0">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</p>
          <span class="text-grey text-sm">{{ $doctor->specialization }}</span>

          {{-- Status --}}
          <div style="margin-top:.5rem">
            @if($doctor->status === 'available')
            <span style="font-size:.72rem;font-weight:600;color:var(--primary);
                                     background:var(--primary-light);padding:.2rem .65rem;
                                     border-radius:var(--radius-full)">● Available</span>
            @elseif($doctor->status === 'onleave')
            <span style="font-size:.72rem;font-weight:600;color:#D97706;
                                     background:#fffbeb;padding:.2rem .65rem;
                                     border-radius:var(--radius-full)">● On Leave</span>
            @else
            <span style="font-size:.72rem;font-weight:600;color:#DC2626;
                                     background:#fef2f2;padding:.2rem .65rem;
                                     border-radius:var(--radius-full)">● Unavailable</span>
            @endif
          </div>

          {{-- Action buttons --}}
          <div style="margin-top:.85rem;display:flex;gap:8px;flex-wrap:wrap">
            @auth
            @if(Auth::user()->role === 'patient')
            @if($doctor->status === 'available')
            <a href="{{ route('patient.appointments.book', $doctor->DoctorID) }}"
              class="btn btn-primary"
              style="padding:.4rem .9rem;font-size:.8rem;border-radius:var(--radius-full)">
              📅 Book Now
            </a>
            @else
            <span style="display:inline-flex;align-items:center;padding:.4rem .9rem;
                                             background:var(--gray-50);color:var(--gray-400);
                                             border:1px solid var(--gray-100);border-radius:var(--radius-full);
                                             font-size:.8rem;font-weight:500;cursor:not-allowed">
              📅 Unavailable
            </span>
            @endif
            @else
            <span title="Only patients can book"
              style="display:inline-flex;align-items:center;padding:.4rem .9rem;
                                       background:var(--gray-50);color:var(--gray-400);
                                       border:1px solid var(--gray-100);border-radius:var(--radius-full);
                                       font-size:.8rem;font-weight:500;cursor:not-allowed">
              📅 Book Now
            </span>
            @endif
            @else
            <a href="{{ route('login') }}"
              class="btn btn-primary"
              style="padding:.4rem .9rem;font-size:.8rem;border-radius:var(--radius-full)">
              📅 Book Now
            </a>
            @endauth

            <a href="{{ route('patient.appointments.doctor-profile', $doctor->DoctorID) }}"
              style="display:inline-flex;align-items:center;padding:.4rem .9rem;
                                   background:transparent;color:var(--primary);
                                   border:1.5px solid var(--primary);border-radius:var(--radius-full);
                                   font-size:.8rem;font-weight:500;text-decoration:none;
                                   transition:all .2s"
              onmouseover="this.style.background='var(--primary)';this.style.color='#fff'"
              onmouseout="this.style.background='transparent';this.style.color='var(--primary)'">
              View Profile
            </a>
          </div>
        </div>
      </div>

      @empty
      <div style="grid-column:1/-1;text-align:center;padding:3rem;color:var(--gray-400)">
        <div style="font-size:48px;margin-bottom:.75rem">👨‍⚕️</div>
        No doctors available at the moment.
      </div>
      @endforelse
    </div>

    <div class="text-center mt-5">
      <a href="{{ route('doctors') }}" class="btn btn-primary">View All Doctors</a>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════
     HOW IT WORKS
══════════════════════════════════════ --}}
<div class="page-section bg-light" id="how_it_works">
  <div class="container">
    <h2 class="text-center mb-5">How It Works</h2>
    <div class="row justify-content-center text-center">

      <div class="col-lg-3 col-sm-6 py-3">
        <div class="card-service" style="flex-direction:column;align-items:center;
                            padding:2.5rem 1.5rem;height:100%">
          <div class="circle-shape bg-primary text-white" style="margin-bottom:1.25rem;
                                font-size:1.5rem;width:64px;height:64px">👤</div>
          <div style="font-size:1.5rem;font-weight:700;color:var(--primary);margin-bottom:.35rem">1</div>
          <h3 style="font-size:.9375rem;margin-bottom:.65rem">Create Account</h3>
          <p class="text-grey text-sm" style="line-height:1.6;margin:0">
            Register as a patient in minutes with your basic information.
          </p>
        </div>
      </div>

      <div class="col-lg-3 col-sm-6 py-3">
        <div class="card-service" style="flex-direction:column;align-items:center;
                            padding:2.5rem 1.5rem;height:100%">
          <div class="circle-shape bg-secondary text-white" style="margin-bottom:1.25rem;
                                font-size:1.5rem;width:64px;height:64px">🔍</div>
          <div style="font-size:1.5rem;font-weight:700;color:var(--primary);margin-bottom:.35rem">2</div>
          <h3 style="font-size:.9375rem;margin-bottom:.65rem">Find a Doctor</h3>
          <p class="text-grey text-sm" style="line-height:1.6;margin:0">
            Browse qualified doctors by specialization and availability.
          </p>
        </div>
      </div>

      <div class="col-lg-3 col-sm-6 py-3">
        <div class="card-service" style="flex-direction:column;align-items:center;
                            padding:2.5rem 1.5rem;height:100%">
          <div class="circle-shape bg-accent text-white" style="margin-bottom:1.25rem;
                                font-size:1.5rem;width:64px;height:64px">📅</div>
          <div style="font-size:1.5rem;font-weight:700;color:var(--primary);margin-bottom:.35rem">3</div>
          <h3 style="font-size:.9375rem;margin-bottom:.65rem">Book Appointment</h3>
          <p class="text-grey text-sm" style="line-height:1.6;margin:0">
            Select your preferred date and time and submit your booking.
          </p>
        </div>
      </div>

      <div class="col-lg-3 col-sm-6 py-3">
        <div class="card-service" style="flex-direction:column;align-items:center;
                            padding:2.5rem 1.5rem;height:100%">
          <div class="circle-shape bg-primary text-white" style="margin-bottom:1.25rem;
                                font-size:1.5rem;width:64px;height:64px">✅</div>
          <div style="font-size:1.5rem;font-weight:700;color:var(--primary);margin-bottom:.35rem">4</div>
          <h3 style="font-size:.9375rem;margin-bottom:.65rem">Get Confirmed</h3>
          <p class="text-grey text-sm" style="line-height:1.6;margin:0">
            Your doctor reviews and approves your appointment request.
          </p>
        </div>
      </div>

    </div>
  </div>
</div>



new = '''{{-- ══════════════════════════════════════
     BOOK APPOINTMENT
══════════════════════════════════════ --}}
<div class="page-section" id="appointment">
  <div class="container">
    <h2 class="text-center">Book an Appointment</h2>
    <p class="text-center text-grey" style="margin-top:.75rem;margin-bottom:3rem">
      Ready to get started? Follow these simple steps to book with one of our doctors.
    </p>

    {{-- 3 Step Cards --}}
    <div class="row justify-content-center" style="margin-bottom:2rem">

      <div class="col-lg-4 col-sm-6 py-3">
        <div class="card-service" style="flex-direction:column;align-items:center;
                            text-align:center;padding:2rem 1.5rem;height:100%;position:relative">
          <div style="position:absolute;top:1rem;right:1rem;
                                width:26px;height:26px;border-radius:50%;
                                background:var(--primary-light);color:var(--primary);
                                display:flex;align-items:center;justify-content:center;
                                font-size:.8rem;font-weight:700">1</div>
          <div class="circle-shape bg-primary text-white"
            style="margin-bottom:1.25rem;font-size:1.5rem;width:60px;height:60px">
            🔍
          </div>
          <h3 style="font-size:.9375rem;font-weight:600;margin-bottom:.65rem;
                               color:var(--gray-900)">Find a Doctor</h3>
          <p class="text-grey text-sm" style="line-height:1.6;margin:0">
            Browse our qualified doctors by specialization and availability.
          </p>
        </div>
      </div>

      <div class="col-lg-4 col-sm-6 py-3">
        <div class="card-service" style="flex-direction:column;align-items:center;
                            text-align:center;padding:2rem 1.5rem;height:100%;position:relative">
          <div style="position:absolute;top:1rem;right:1rem;
                                width:26px;height:26px;border-radius:50%;
                                background:var(--primary-light);color:var(--primary);
                                display:flex;align-items:center;justify-content:center;
                                font-size:.8rem;font-weight:700">2</div>
          <div class="circle-shape bg-secondary text-white"
            style="margin-bottom:1.25rem;font-size:1.5rem;width:60px;height:60px">
            📅
          </div>
          <h3 style="font-size:.9375rem;font-weight:600;margin-bottom:.65rem;
                               color:var(--gray-900)">Choose Date & Time</h3>
          <p class="text-grey text-sm" style="line-height:1.6;margin:0">
            Pick a date and time slot that works best for you.
          </p>
        </div>
      </div>

      <div class="col-lg-4 col-sm-6 py-3">
        <div class="card-service" style="flex-direction:column;align-items:center;
                            text-align:center;padding:2rem 1.5rem;height:100%;position:relative">
          <div style="position:absolute;top:1rem;right:1rem;
                                width:26px;height:26px;border-radius:50%;
                                background:var(--primary-light);color:var(--primary);
                                display:flex;align-items:center;justify-content:center;
                                font-size:.8rem;font-weight:700">3</div>
          <div class="circle-shape bg-accent text-white"
            style="margin-bottom:1.25rem;font-size:1.5rem;width:60px;height:60px">
            ✅
          </div>
          <h3 style="font-size:.9375rem;font-weight:600;margin-bottom:.65rem;
                               color:var(--gray-900)">Get Confirmed</h3>
          <p class="text-grey text-sm" style="line-height:1.6;margin:0">
            Your doctor reviews and approves your appointment request.
          </p>
        </div>
      </div>

    </div>

    {{-- CTA Box --}}
    <div class="main-form text-center" style="max-width:560px;margin:0 auto;padding:2.5rem 2rem">
      @auth
      @if(Auth::user()->role === 'patient')
      <h3 style="margin-bottom:.65rem;color:var(--gray-900)">Ready to Book?</h3>
      <p class="text-grey" style="margin-bottom:1.5rem">
        Browse our available doctors and choose the one that fits your needs.
      </p>
      <a href="{{ route('patient.appointments.find-doctors') }}" class="btn btn-primary">
        Browse Doctors & Book
      </a>
      @else
      <h3 style="margin-bottom:.65rem;color:var(--gray-900)">Want to Book?</h3>
      <p class="text-grey" style="margin-bottom:0">
        Only patients can book appointments.<br>
        Please log in with a patient account.
      </p>
      @endif
      @else
      <h3 style="margin-bottom:.65rem;color:var(--gray-900)">Get Started Today</h3>
      <p class="text-grey" style="margin-bottom:1.5rem">
        Create a free account or login to book with any of our doctors.
      </p>
      <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap">
        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
        <a href="{{ route('register') }}"
          style="display:inline-flex;align-items:center;padding:12px 28px;
                           border:2px solid var(--primary);color:var(--primary);
                           border-radius:var(--radius-full);font-weight:500;
                           text-decoration:none;background:transparent;transition:all .2s"
          onmouseover="this.style.background=\'var(--primary)\';this.style.color=\'#fff\'"
          onmouseout="this.style.background=\'transparent\';this.style.color=\'var(--primary)\'">
          Create Account
        </a>
      </div>
      @endauth
    </div>

  </div>
</div>

{{-- ══════════════════════════════════════
     CTA BANNER
══════════════════════════════════════ --}}
<div class="banner-home">
  <div class="container py-5">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <h1 style="color:#fff;margin-bottom:1.25rem">
          Ready to take control of your health?
        </h1>
        <p style="color:rgba(255,255,255,.7);margin-bottom:2rem;font-size:1rem;max-width:500px">
          Join thousands of patients who trust One Health for their medical care.
          Book appointments, track your health, and connect with top doctors.
        </p>
        <div style="display:flex;gap:1rem;flex-wrap:wrap">
          @guest
          <a href="{{ route('register') }}" class="btn btn-primary">
            Get Started Free
          </a>
          @endguest
          <a href="#our_doctor"
            style="display:inline-flex;align-items:center;padding:12px 28px;
                               border:2px solid rgba(255,255,255,.5);color:#fff;
                               border-radius:var(--radius-full);font-weight:500;
                               text-decoration:none;transition:border-color .2s"
            onmouseover="this.style.borderColor='#fff'"
            onmouseout="this.style.borderColor='rgba(255,255,255,.5)'">
            View Doctors
          </a>
        </div>
      </div>
      <div class="col-lg-4 d-none d-lg-block text-center">
        <div style="font-size:120px;opacity:.85;margin-top:-20px">🏥</div>
      </div>
    </div>
  </div>
</div>

@endsection