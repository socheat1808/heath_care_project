<!-- TOPBAR -->
<div class="topbar">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-sm-8 text-sm">
        <div class="site-info">
          <a href="#">📞 +00 123 4455 6666</a>
          <span class="divider">|</span>
          <a href="#">✉️ mail@example.com</a>
        </div>
      </div>
      <div class="col-sm-4 text-right text-sm">
        <div class="social-mini-button">
          <a href="#">f</a>
          <a href="#">t</a>
          <a href="#">d</a>
          <a href="#">in</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- NAVBAR -->
<nav class="navbar" id="mainNav">
  <div class="container">
    <a class="navbar-brand" href="#">
      <span class="text-primary">One</span>-Health
    </a>
    <div class="input-navbar" style="flex:0 0 auto">
      <div class="input-group-text">🔍</div>
      <input type="text" class="form-control" placeholder="Enter keyword..">
    </div>
    <button class="navbar-toggler" onclick="document.getElementById('nav').classList.toggle('show')">
      <svg width="22" height="14" viewBox="0 0 22 14" fill="none">
        <rect y="0" width="22" height="2" rx="1" fill="currentColor" />
        <rect y="6" width="16" height="2" rx="1" fill="currentColor" />
        <rect y="12" width="22" height="2" rx="1" fill="currentColor" />
      </svg>
    </button>
    <div class="navbar-collapse" id="nav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item "><a class="nav-link" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="{{route('home')}}#about_us">About Us</a></li>
        <li class="nav-item"><a class="nav-link" href="{{route('home')}}#our_doctor">Doctors</a></li>
        <li class="nav-item"><a class="nav-link" href="{{route('home')}}#blog_new">News</a></li>
        <li class="nav-item"><a class="nav-link" href="{{route('home')}}#appointment">Contact</a></li>

        @auth
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
        @elseif(Auth::user()->role === 'doctor')
        <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
        @else
        {{-- patient has no dashboard, just show logout --}}
        <a href="{{ route('home') }}" class="nav-link">Home</a>
        @endif

        <form method="POST" action="{{ route('logout') }}" style="display:inline">
          @csrf
          <button type="submit" class="nav-link" style="background:none;border:none;cursor:pointer;padding:0">
            Log out
          </button>
        </form>
        @else
        <a href="{{ route('login') }}" class="nav-link">Log in</a>
        @if(Route::has('register'))
        <a href="{{ route('register') }}" class="nav-link">Register</a>
        @endif
        @endauth
      </ul>
    </div>
  </div>
</nav>