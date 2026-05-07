<!-- <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <meta name="copyright" content="MACode ID, https://macodeid.com/">

  <title>One Health - Medical Center HTML5 Template</title>

  <link rel="stylesheet" href="../assets/css/maicons.css">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="../assets/vendor/owl-carousel/css/owl.carousel.css">

  <link rel="stylesheet" href="../assets/vendor/animate/animate.css">

  <link rel="stylesheet" href="../assets/css/theme.css">
</head>
<body> -->

<!-- Back to top button -->

@extends('layout.main')

@section('content')
<!-- HERO -->
<div class="page-hero overlay-dark" style="background:linear-gradient(135deg,#0d2137 0%,#1a4a36 100%)">
  <div class="hero-section">
    <div class="container text-center">
      <span class="subhead">Let's make your life happier</span>
      <h1>Healthy Living</h1>
      <a href="#" class="btn btn-primary">Let's Consult</a>
    </div>
  </div>
</div>

<!-- SERVICE CARDS -->
<div class="bg-light">
  <div class="page-section" style="padding-top:0">
    <div style="margin-top:-3rem;position:relative;z-index:10;padding:0 0 0">
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
              <div class="circle-shape bg-accent text-white">🛒</div>
              <p><span>One</span>-Health Pharmacy</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- WELCOME -->
    <div class="page-section pb-0" style="margin-top:0" id="about_us">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6 py-3">
            <h1>Welcome to Your Health <br>Center</h1>
            <p class="text-grey mb-4" style="margin-top:16px">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Accusantium aperiam earum.</p>
            <a href="#" class="btn btn-primary">Learn More</a>
          </div>
          <div class="col-lg-6">
            <div class="img-place custom-img-1">
              <!-- Placeholder doctor illustration -->
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

<!-- DOCTORS -->
<div class="page-section" id="our_doctor">
  <div class="container">
    <h2 class="text-center mb-5">Our Doctors</h2>
    <div class="owl-carousel" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:24px">
      <div class="card-doctor">
        <div class="header">
          <div style="width:100%;height:260px;background:linear-gradient(135deg,#E8F7F3 0%,#d1ede6 100%);display:flex;align-items:center;justify-content:center;font-size:72px">👨‍⚕️</div>
          <div class="meta">
            <a href="#">📞</a>
            <a href="#">💬</a>
          </div>
        </div>
        <div class="body">
          <p class="text-xl mb-0">Dr. Stein Albert</p>
          <span class="text-grey text-sm">Cardiology</span>
        </div>
      </div>
      <div class="card-doctor">
        <div class="header">
          <div style="width:100%;height:260px;background:linear-gradient(135deg,#EEF2FF 0%,#dde4f7 100%);display:flex;align-items:center;justify-content:center;font-size:72px">👩‍⚕️</div>
          <div class="meta">
            <a href="#">📞</a>
            <a href="#">💬</a>
          </div>
        </div>
        <div class="body">
          <p class="text-xl mb-0">Dr. Alexa Melvin</p>
          <span class="text-grey text-sm">Dental</span>
        </div>
      </div>
      <div class="card-doctor">
        <div class="header">
          <div style="width:100%;height:260px;background:linear-gradient(135deg,#FFF5EE 0%,#fce4d6 100%);display:flex;align-items:center;justify-content:center;font-size:72px">🧑‍⚕️</div>
          <div class="meta">
            <a href="#">📞</a>
            <a href="#">💬</a>
          </div>
        </div>
        <div class="body">
          <p class="text-xl mb-0">Dr. Rebecca Steffany</p>
          <span class="text-grey text-sm">General Health</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- BLOG -->
<div class="page-section bg-light" id="blog_new">
  <div class="container">
    <h2 class="text-center">Latest News</h2>
    <div class="row mt-5">
      <div class="col-lg-4 py-2">
        <div class="card-blog">
          <div class="header">
            <div class="post-category"><a href="#">Covid19</a></div>
            <a href="#" class="post-thumb">
              <div style="width:100%;height:210px;background:linear-gradient(135deg,#e8f4fd,#c5e3f7);display:flex;align-items:center;justify-content:center;font-size:60px">🌍</div>
            </a>
          </div>
          <div class="body">
            <h5 class="post-title" style="color:var(--gray-900);text-transform:none;letter-spacing:0;font-family:var(--font-body)">
              <a href="#">List of Countries without Coronavirus case</a>
            </h5>
            <div class="site-info">
              <div class="avatar mr-2">
                <div class="avatar-img">RA</div>
                <span>Roger Adams</span>
              </div>
              ⏱ 1 week ago
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 py-2">
        <div class="card-blog">
          <div class="header">
            <div class="post-category"><a href="#">Covid19</a></div>
            <a href="#" class="post-thumb">
              <div style="width:100%;height:210px;background:linear-gradient(135deg,#f0fdf4,#bbf7d0);display:flex;align-items:center;justify-content:center;font-size:60px">📰</div>
            </a>
          </div>
          <div class="body">
            <h5 class="post-title" style="color:var(--gray-900);text-transform:none;letter-spacing:0;font-family:var(--font-body)">
              <a href="#">Recovery Room: News beyond the pandemic</a>
            </h5>
            <div class="site-info">
              <div class="avatar mr-2">
                <div class="avatar-img">RA</div>
                <span>Roger Adams</span>
              </div>
              ⏱ 4 weeks ago
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 py-2">
        <div class="card-blog">
          <div class="header">
            <div class="post-category"><a href="#">Health</a></div>
            <a href="#" class="post-thumb">
              <div style="width:100%;height:210px;background:linear-gradient(135deg,#fff7ed,#fed7aa);display:flex;align-items:center;justify-content:center;font-size:60px">🍭</div>
            </a>
          </div>
          <div class="body">
            <h5 class="post-title" style="color:var(--gray-900);text-transform:none;letter-spacing:0;font-family:var(--font-body)">
              <a href="#">What is the impact of eating too much sugar?</a>
            </h5>
            <div class="site-info">
              <div class="avatar mr-2">
                <div class="avatar-img">DS</div>
                <span>Diego Simmons</span>
              </div>
              ⏱ 2 months ago
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 text-center mt-4">
        <a href="#" class="btn btn-primary">Read More</a>
      </div>
    </div>
  </div>
</div>

<!-- APPOINTMENT FORM -->
<div class="page-section" id="appointment">
  <div class="container">
    <h2 class="text-center">Make an Appointment</h2>
    <div class="main-form" style="margin-top:3rem">
      <div class="row mt-5">
        <div class="col-12 col-sm-6 py-2">
          <input type="text" class="form-control" placeholder="Full name">
        </div>
        <div class="col-12 col-sm-6 py-2">
          <input type="text" class="form-control" placeholder="Email address..">
        </div>
        <div class="col-12 col-sm-6 py-2">
          <input type="date" class="form-control">
        </div>
        <div class="col-12 col-sm-6 py-2">
          <select class="custom-select">
            <option>General Health</option>
            <option>Cardiology</option>
            <option>Dental</option>
            <option>Neurology</option>
            <option>Orthopaedics</option>
          </select>
        </div>
        <div class="col-12 py-2">
          <input type="text" class="form-control" placeholder="Phone number..">
        </div>
        <div class="col-12 py-2">
          <textarea class="form-control" rows="6" placeholder="Enter message.."></textarea>
        </div>
      </div>
      <button type="submit" class="btn btn-primary" style="margin-top:1.5rem">Submit Request</button>
    </div>
  </div>
</div>

<!-- APP BANNER -->
<div class="banner-home" id="app_banner">
  <div class="container py-5">
    <div class="row align-items-center">
      <div class="col-lg-4">
        <div class="img-banner d-none d-lg-block">
          <div style="display:flex;align-items:flex-end;justify-content:center;height:320px;font-size:160px;transform:translateY(-20px)">📱</div>
        </div>
      </div>
      <div class="col-lg-8">
        <h1 class="font-weight-normal mb-3">Get easy access of all features using One Health Application</h1>
        <a href="#" class="store-btn">
          <svg viewBox="0 0 24 24" fill="white">
            <path d="M3 3l18 9-18 9V3z" />
          </svg>
          <div class="store-btn-text">
            <span>Get it on</span>
            <span>Google Play</span>
          </div>
        </a>
        <a href="#" class="store-btn">
          <svg viewBox="0 0 24 24" fill="white">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z" />
          </svg>
          <div class="store-btn-text">
            <span>Download on the</span>
            <span>App Store</span>
          </div>
        </a>
      </div>
    </div>
  </div>
</div>
@endsection



<!-- <script src="../assets/js/jquery-3.5.1.min.js"></script>

<script src="../assets/js/bootstrap.bundle.min.js"></script>

<script src="../assets/vendor/owl-carousel/js/owl.carousel.min.js"></script>

<script src="../assets/vendor/wow/wow.min.js"></script>

<script src="../assets/js/theme.js"></script>
  
</body>
</html> -->