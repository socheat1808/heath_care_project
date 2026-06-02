<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Healthcare</title>

    {{-- Google Fonts (loaded in CSS via @import, but listed here as fallback) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- Maicons icon font --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    {{-- Owl Carousel CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/owl-carousel/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/owl-carousel/css/owl.theme.default.min.css') }}">

    {{-- Animate.css (for WOW.js) --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/animate/animate.min.css') }}">

    <!-- {{-- ✅ Our clean stylesheet --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"> -->

    @stack('style')
</head>

<body>
    @include('layouts.navbar')
    @if(session('error'))
    <div style="position:fixed;top:1rem;right:1rem;z-index:9999;
                background:#fef2f2;border:1px solid #fecaca;border-radius:12px;
                padding:.85rem 1.1rem;font-size:.875rem;color:#b91c1c;
                display:flex;align-items:center;gap:.65rem;
                box-shadow:0 4px 12px rgba(0,0,0,0.1);max-width:360px">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;flex-shrink:0">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {{ session('error') }}
    </div>
    @endif
    @yield('content')

    @include('layouts.footer')

    {{-- ========== SCRIPTS ========== --}}
    {{-- jQuery --}}
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>

    {{-- Owl Carousel --}}
    <script src="{{ asset('assets/vendor/owl-carousel/js/owl.carousel.min.js') }}"></script>

    {{-- WOW.js --}}
    <script src="{{ asset('assets/vendor/wow/wow.min.js') }}"></script>

    {{-- Main app script --}}
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('app')

</body>

</html>