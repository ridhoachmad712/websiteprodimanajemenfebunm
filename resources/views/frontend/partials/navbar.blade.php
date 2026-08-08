{{-- Navbar publik — gaya Tabler "navbar sticky": dua baris (brand + menu),
     seluruhnya sticky. Tanpa ikon; penanda menu aktif memakai warna.
     Item menu dirender dari tabel menus via View Composer ($mainMenu). --}}
@php
    // Penanda menu aktif berdasarkan path saat ini.
    $isActive = function ($url) {
        if (! $url || $url === '#') return false;
        $parts = parse_url($url);
        // Tautan eksternal (host berbeda) tidak pernah ditandai aktif.
        if (! empty($parts['host']) && $parts['host'] !== request()->getHost()) {
            return false;
        }
        $p = trim($parts['path'] ?? '', '/');
        return $p === '' ? request()->is('/') : request()->is($p, $p.'/*');
    };

    $telepon = \App\Models\Setting::get('kontak.telepon');
    $email   = \App\Models\Setting::get('kontak.email');
    $ig = \App\Models\Setting::get('sosmed.instagram');
    $tt = \App\Models\Setting::get('sosmed.tiktok');
    $fb = \App\Models\Setting::get('sosmed.facebook');

    // Pengaturan tampilan navbar (dengan default).
    $d = \App\Http\Controllers\Admin\AppearanceController::defaults();
    $cfg = fn ($k) => \App\Models\Setting::get($k, $d[$k] ?? null);
    $navLogo     = $cfg('navbar.logo');
    $navMark     = $cfg('navbar.brand_mark');
    $navText     = $cfg('navbar.brand_text');
    $navSub      = $cfg('navbar.brand_subtext');
    $navSticky   = $cfg('navbar.sticky') === '1';
    $navTopbar   = $cfg('navbar.show_topbar') === '1';
    $navCenter   = $cfg('navbar.menu_center') === '1';
    $navButtons  = \App\Http\Controllers\Admin\AppearanceController::navbarButtons();
@endphp

{{-- Top utility bar --}}
@if ($navTopbar)
<div class="topbar d-none d-md-block">
    <div class="container-xl d-flex justify-content-between align-items-center">
        <div class="d-flex gap-3">
            @if ($telepon)<span><i class="ti ti-phone me-1"></i>{{ $telepon }}</span>@endif
            @if ($email)<a href="mailto:{{ $email }}" class="topbar-link"><i class="ti ti-mail me-1"></i>{{ $email }}</a>@endif
        </div>
        <div class="d-flex gap-2 align-items-center">
            @if ($ig)<a href="{{ $ig }}" target="_blank" rel="noopener" class="topbar-link" aria-label="Instagram"><i class="ti ti-brand-instagram"></i></a>@endif
            @if ($tt)<a href="{{ $tt }}" target="_blank" rel="noopener" class="topbar-link" aria-label="TikTok"><i class="ti ti-brand-tiktok"></i></a>@endif
            @if ($fb)<a href="{{ $fb }}" target="_blank" rel="noopener" class="topbar-link" aria-label="Facebook"><i class="ti ti-brand-facebook"></i></a>@endif
        </div>
    </div>
</div>
@endif

<div class="site-header {{ $navSticky ? 'sticky-top' : '' }} {{ request()->routeIs('home') ? 'site-header--overlay' : '' }}">
    {{-- Baris 1: brand + aksi --}}
    <header class="navbar navbar-expand-xl d-print-none site-navbar">
        <div class="container-xl">
            <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center text-reset text-decoration-none m-0">
                @if ($navLogo)
                    <img src="{{ Storage::url($navLogo) }}" alt="{{ $navText }}">
                @else
                    @if ($navMark)<span class="brand-mark me-2">{{ $navMark }}</span>@endif
                    <span class="d-flex flex-column lh-1">
                        <span class="fw-bold">{{ $navText }}</span>
                        @if ($navSub)<span class="text-secondary" style="font-size:.7rem;letter-spacing:.04em">{{ $navSub }}</span>@endif
                    </span>
                @endif
            </a>

            @if (count($navButtons))
                <div class="navbar-nav flex-row ms-auto">
                    <div class="d-none d-xl-flex align-items-center gap-2">
                        @foreach ($navButtons as $b)
                            <a href="{{ $b['url'] ?: '#' }}" class="btn btn-{{ $b['color'] ?? 'primary' }}">{{ $b['label'] }}</a>
                        @endforeach
                    </div>
                </div>
            @endif

            <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Buka menu">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </header>

    {{-- Baris 2: menu utama --}}
    <div class="navbar-expand-xl site-navmenu {{ $navCenter ? 'is-centered' : '' }} d-print-none">
        <div class="collapse navbar-collapse" id="navbar-menu">
            <div class="navbar">
                <div class="container-xl">
                    <ul class="navbar-nav">
                        @foreach ($mainMenu as $item)
                            @if ($item->activeChildren->isNotEmpty())
                                @php($childActive = $item->activeChildren->contains(fn ($c) => $isActive($c->href)))
                                <li class="nav-item dropdown {{ $childActive ? 'active' : '' }}">
                                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">{{ $item->title }}</a>
                                    <div class="dropdown-menu">
                                        @include('frontend.partials.menu-dropdown', ['items' => $item->activeChildren])
                                    </div>
                                </li>
                            @else
                                <li class="nav-item {{ $isActive($item->href) ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ $item->href }}" @if($item->target === '_blank') target="_blank" rel="noopener" @endif>{{ $item->title }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>

                    <form class="d-flex ms-xl-auto my-2 my-xl-0 site-search" role="search" action="{{ route('search.index') }}" method="GET">
                        <div class="input-icon">
                            <span class="input-icon-addon"><i class="ti ti-search"></i></span>
                            <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari…" aria-label="Cari di situs">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
