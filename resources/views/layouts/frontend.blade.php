<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title', 'Beranda') &mdash; {{ config('app.name') }}</title>

    @include('frontend.partials.seo')

    {{-- Tabler core + icons + brand override + tema landing --}}
    <link href="{{ asset('tabler/css/tabler.min.css') }}" rel="stylesheet">
    <link href="{{ asset('tabler/icons/tabler-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('tabler/css/brand.css') }}" rel="stylesheet">
    <link href="{{ asset('tabler/css/frontend.css') }}" rel="stylesheet">

    {{-- Font: Plus Jakarta Sans (judul) + Inter (teks) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    @stack('styles')
</head>
<body>
    {{-- Theme bootstrap (light/dark, sebelum render body) --}}
    <script src="{{ asset('tabler/js/tabler-theme.min.js') }}"></script>

    <div class="page">
        @include('frontend.partials.navbar')

        <main id="content">
            @yield('content')
        </main>

        @include('frontend.partials.footer')
    </div>

    <script src="{{ asset('tabler/js/tabler.min.js') }}" defer></script>

    {{-- Interaksi landing: reveal-on-scroll, animasi counter, navbar saat scroll --}}
    <script>
        (function () {
            // Navbar: tambah bayangan saat halaman di-scroll
            var nav = document.querySelector('.site-navbar');
            if (nav) {
                var onScroll = function () { nav.classList.toggle('is-scrolled', window.scrollY > 8); };
                onScroll(); window.addEventListener('scroll', onScroll, { passive: true });
            }

            // Reveal-on-scroll
            var reveals = document.querySelectorAll('[data-reveal]');
            if ('IntersectionObserver' in window && reveals.length) {
                var io = new IntersectionObserver(function (entries) {
                    entries.forEach(function (e) {
                        if (e.isIntersecting) { e.target.classList.add('is-visible'); io.unobserve(e.target); }
                    });
                }, { threshold: 0.12 });
                reveals.forEach(function (el) { io.observe(el); });
            } else {
                reveals.forEach(function (el) { el.classList.add('is-visible'); });
            }

            // Counter angka beranimasi
            var counters = document.querySelectorAll('[data-count]');
            var animate = function (el) {
                var target = parseFloat(el.getAttribute('data-count')) || 0;
                var dur = 1200, start = null;
                var step = function (ts) {
                    if (!start) start = ts;
                    var p = Math.min((ts - start) / dur, 1);
                    el.textContent = Math.floor(p * target).toLocaleString('id-ID');
                    if (p < 1) requestAnimationFrame(step);
                };
                requestAnimationFrame(step);
            };
            if ('IntersectionObserver' in window && counters.length) {
                var io2 = new IntersectionObserver(function (entries) {
                    entries.forEach(function (e) {
                        if (e.isIntersecting) { animate(e.target); io2.unobserve(e.target); }
                    });
                }, { threshold: 0.5 });
                counters.forEach(function (el) { io2.observe(el); });
            }
        })();
    </script>
    @stack('scripts')
</body>
</html>
