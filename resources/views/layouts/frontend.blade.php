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
    @include('frontend.partials.analytics')

    {{-- Font default Tabler: Geist --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    @stack('styles')
</head>
<body>
    {{-- Theme bootstrap (light/dark, sebelum render body) --}}
    <script src="{{ asset('tabler/js/tabler-theme.min.js') }}"></script>

    <a href="#content" class="skip-link">Lewati ke konten utama</a>

    <div class="page">
        @include('frontend.partials.announcement-bar')
        @include('frontend.partials.navbar')

        <main id="content" tabindex="-1">
            @yield('content')
        </main>

        @include('frontend.partials.footer')
    </div>

    <button type="button" class="back-to-top" id="backToTop" aria-label="Kembali ke atas">
        <i class="ti ti-arrow-up"></i>
    </button>

    <div class="floating-actions notranslate">
    {{-- Toolbar aksesibilitas --}}
    <div class="accessibility-widget" id="accessibilityWidget">
        <button type="button" class="btn btn-primary accessibility-toggle" id="accessibilityToggle" aria-expanded="false" aria-controls="accessibilityPanel" aria-label="Buka pengaturan aksesibilitas">
            <i class="ti ti-accessible fs-3"></i>
            <span class="floating-action-text">Aksesibilitas</span>
        </button>
        <div class="accessibility-panel shadow" id="accessibilityPanel" hidden>
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h2 class="h4 m-0">Aksesibilitas</h2>
                <button type="button" class="btn btn-icon btn-sm" id="accessibilityClose" aria-label="Tutup pengaturan aksesibilitas">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <div class="accessibility-actions">
                <button type="button" class="btn btn-outline-primary w-100" data-a11y-toggle="largeText" aria-pressed="false">
                    <i class="ti ti-text-size me-2"></i>Perbesar teks
                </button>
                <button type="button" class="btn btn-outline-primary w-100" data-a11y-toggle="highContrast" aria-pressed="false">
                    <i class="ti ti-contrast me-2"></i>Kontras tinggi
                </button>
                <button type="button" class="btn btn-outline-primary w-100" data-a11y-toggle="grayscale" aria-pressed="false">
                    <i class="ti ti-color-filter me-2"></i>Grayscale
                </button>
                <button type="button" class="btn btn-outline-primary w-100" data-a11y-toggle="underlineLinks" aria-pressed="false">
                    <i class="ti ti-link me-2"></i>Garis bawah link
                </button>
                <button type="button" class="btn btn-outline-primary w-100" data-a11y-toggle="readableFont" aria-pressed="false">
                    <i class="ti ti-letter-case me-2"></i>Font mudah dibaca
                </button>
                <button type="button" class="btn btn-outline-primary w-100" data-a11y-toggle="reduceMotion" aria-pressed="false">
                    <i class="ti ti-player-pause me-2"></i>Kurangi animasi
                </button>
            </div>
            <button type="button" class="btn btn-ghost-secondary w-100 mt-2" id="accessibilityReset">
                <i class="ti ti-restore me-2"></i>Reset
            </button>
        </div>
    </div>

    {{-- Tombol pilih bahasa melayang (kanan bawah) --}}
    <div class="lang-fab dropup">
        <button type="button" class="btn btn-primary lang-fab-btn" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Ubah bahasa">
            <i class="ti ti-language fs-3"></i>
            <span class="floating-action-text lang-fab-text">Bahasa</span>
        </button>
        <div class="dropdown-menu dropdown-menu-end shadow">
            <h6 class="dropdown-header"><i class="ti ti-world me-1"></i>Pilih Bahasa / Language</h6>
            @foreach (['id' => 'Indonesia', 'en' => 'English', 'ar' => 'العربية', 'zh-CN' => '中文 (Mandarin)', 'ja' => '日本語', 'ko' => '한국어', 'fr' => 'Français', 'de' => 'Deutsch'] as $code => $name)
                <a class="dropdown-item" href="#" onclick="setSiteLang('{{ $code }}'); return false;">{{ $name }}</a>
            @endforeach
        </div>
    </div>
    </div>

    <script src="{{ asset('tabler/js/tabler.min.js') }}" defer></script>

    {{-- Interaksi landing: reveal-on-scroll, animasi counter, navbar saat scroll --}}
    <script>
        (function () {
            var html = document.documentElement;
            var storageKey = 'frontendAccessibility';
            var defaults = {
                largeText: false,
                highContrast: false,
                grayscale: false,
                underlineLinks: false,
                readableFont: false,
                reduceMotion: false
            };
            var classMap = {
                largeText: 'a11y-large-text',
                highContrast: 'a11y-high-contrast',
                grayscale: 'a11y-grayscale',
                underlineLinks: 'a11y-underline-links',
                readableFont: 'a11y-readable-font',
                reduceMotion: 'a11y-reduce-motion'
            };
            var state = Object.assign({}, defaults);

            try {
                state = Object.assign(state, JSON.parse(localStorage.getItem(storageKey) || '{}'));
            } catch (e) {}

            var saveA11y = function () {
                try { localStorage.setItem(storageKey, JSON.stringify(state)); } catch (e) {}
            };
            var applyA11y = function () {
                Object.keys(classMap).forEach(function (key) {
                    html.classList.toggle(classMap[key], !!state[key]);
                    document.querySelectorAll('[data-a11y-toggle="' + key + '"]').forEach(function (btn) {
                        btn.setAttribute('aria-pressed', state[key] ? 'true' : 'false');
                        btn.classList.toggle('active', !!state[key]);
                    });
                });
            };
            applyA11y();

            var a11yWidget = document.getElementById('accessibilityWidget');
            var a11yToggle = document.getElementById('accessibilityToggle');
            var a11yPanel = document.getElementById('accessibilityPanel');
            var a11yClose = document.getElementById('accessibilityClose');
            var a11yReset = document.getElementById('accessibilityReset');
            var setA11yPanel = function (open) {
                if (!a11yPanel || !a11yToggle) return;
                a11yPanel.hidden = !open;
                a11yToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            };
            if (a11yToggle) a11yToggle.addEventListener('click', function () { setA11yPanel(a11yPanel.hidden); });
            if (a11yClose) a11yClose.addEventListener('click', function () { setA11yPanel(false); a11yToggle.focus(); });
            if (a11yWidget) {
                a11yWidget.addEventListener('click', function (e) {
                    var btn = e.target.closest('[data-a11y-toggle]');
                    if (!btn) return;
                    var key = btn.getAttribute('data-a11y-toggle');
                    state[key] = !state[key];
                    applyA11y();
                    saveA11y();
                });
            }
            if (a11yReset) {
                a11yReset.addEventListener('click', function () {
                    state = Object.assign({}, defaults);
                    applyA11y();
                    saveA11y();
                });
            }
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && a11yPanel && !a11yPanel.hidden) setA11yPanel(false);
            });
            document.addEventListener('click', function (e) {
                if (a11yWidget && !a11yWidget.contains(e.target)) setA11yPanel(false);
            });

            // Header: tambah bayangan saat halaman di-scroll + tombol kembali ke atas
            var nav = document.querySelector('.site-header');
            var btt = document.getElementById('backToTop');
            var setStickyHeaderOffset = function () {
                var height = nav ? Math.ceil(nav.getBoundingClientRect().height) : 0;
                document.documentElement.style.setProperty('--sticky-header-offset', (height + 24) + 'px');
            };
            var onScroll = function () {
                if (nav) nav.classList.toggle('is-scrolled', window.scrollY > 8);
                if (btt) btt.classList.toggle('show', window.scrollY > 400);
            };
            setStickyHeaderOffset();
            window.addEventListener('resize', setStickyHeaderOffset);
            window.addEventListener('load', setStickyHeaderOffset);
            onScroll(); window.addEventListener('scroll', onScroll, { passive: true });
            if (btt) btt.addEventListener('click', function () {
                window.scrollTo({ top: 0, behavior: html.classList.contains('a11y-reduce-motion') ? 'auto' : 'smooth' });
            });

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
