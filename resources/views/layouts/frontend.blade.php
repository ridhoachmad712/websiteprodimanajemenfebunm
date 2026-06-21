<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- Title tab selalu tetap (tidak berubah per halaman). --}}
    <title>{{ \App\Models\Setting::get('site.nama') ?: config('app.name') }}</title>

    @include('frontend.partials.seo')

    {{-- Tabler core + icons + brand override + tema landing --}}
    <link href="{{ asset('tabler/css/tabler.min.css') }}" rel="stylesheet">
    <link href="{{ asset('tabler/icons/tabler-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('tabler/css/brand.css') }}" rel="stylesheet">
    <link href="{{ asset('tabler/css/frontend.css') }}" rel="stylesheet">
    @include('partials.theme')

    {{-- Font default Tabler: Geist --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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

    <button type="button" class="back-to-top" id="backToTop" aria-label="Kembali ke atas">
        <i class="ti ti-arrow-up"></i>
    </button>

    {{-- Tombol pilih bahasa melayang (kanan bawah) --}}
    <div class="lang-fab dropup notranslate">
        <button type="button" class="btn btn-primary lang-fab-btn" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Ubah bahasa">
            <i class="ti ti-language fs-3"></i>
            <span class="lang-fab-text">Bahasa</span>
        </button>
        <div class="dropdown-menu dropdown-menu-end shadow">
            <h6 class="dropdown-header"><i class="ti ti-world me-1"></i>Pilih Bahasa / Language</h6>
            @foreach (['id' => 'Indonesia', 'en' => 'English', 'ar' => 'العربية', 'zh-CN' => '中文 (Mandarin)', 'ja' => '日本語', 'ko' => '한국어', 'fr' => 'Français', 'de' => 'Deutsch'] as $code => $name)
                <a class="dropdown-item" href="#" onclick="setSiteLang('{{ $code }}'); return false;">{{ $name }}</a>
            @endforeach
        </div>
    </div>

    <script src="{{ asset('tabler/js/tabler.min.js') }}" defer></script>

    {{-- Interaksi landing: reveal-on-scroll, animasi counter, navbar saat scroll --}}
    <script>
        (function () {
            // Header: tambah bayangan saat halaman di-scroll + tombol kembali ke atas
            var nav = document.querySelector('.site-header');
            var btt = document.getElementById('backToTop');
            var onScroll = function () {
                if (nav) nav.classList.toggle('is-scrolled', window.scrollY > 8);
                if (btt) btt.classList.toggle('show', window.scrollY > 400);
            };
            onScroll(); window.addEventListener('scroll', onScroll, { passive: true });
            if (btt) btt.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });

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

    {{-- ===== Google Translate (terjemahan otomatis) ===== --}}
    <div id="google_translate_element"></div>
    <script>
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({ pageLanguage: 'id', autoDisplay: false }, 'google_translate_element');
        }
        // Paksa sembunyikan banner Google di atas + reset geseran body.
        (function () {
            var fix = function () {
                document.querySelectorAll('.goog-te-banner-frame, .skiptranslate iframe').forEach(function (el) { el.style.display = 'none'; });
                if (document.body) document.body.style.top = '0px';
            };
            setInterval(fix, 400);
            if (window.MutationObserver) {
                new MutationObserver(fix).observe(document.documentElement, { childList: true, subtree: true });
            }
        })();
        // Ubah bahasa via dropdown kustom di navbar.
        function setSiteLang(lang) {
            var apply = function () {
                var sel = document.querySelector('.goog-te-combo');
                if (!sel) return false;
                sel.value = lang;
                sel.dispatchEvent(new Event('change'));
                return true;
            };
            if (!apply()) {
                var tries = 0;
                var timer = setInterval(function () {
                    if (apply() || ++tries > 20) clearInterval(timer);
                }, 300);
            }
        }
    </script>
    <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" defer></script>

    @stack('scripts')
</body>
</html>
