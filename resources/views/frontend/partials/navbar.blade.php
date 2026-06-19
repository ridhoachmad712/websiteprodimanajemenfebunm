{{-- Navbar publik — dirender dari tabel menus via View Composer ($mainMenu). --}}
@php
    // Penanda menu aktif berdasarkan path saat ini.
    $isActive = function ($url) {
        if (! $url || $url === '#') return false;
        $p = trim(parse_url($url, PHP_URL_PATH) ?? '', '/');
        return $p === '' ? request()->is('/') : request()->is($p, $p.'/*');
    };
    $telepon = \App\Models\Setting::get('kontak.telepon');
    $email   = \App\Models\Setting::get('kontak.email');
    $ig = \App\Models\Setting::get('sosmed.instagram');
    $tt = \App\Models\Setting::get('sosmed.tiktok');
    $fb = \App\Models\Setting::get('sosmed.facebook');
@endphp

{{-- Top utility bar --}}
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

<header class="navbar navbar-expand-md navbar-light d-print-none sticky-top bg-white site-navbar">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <h1 class="navbar-brand navbar-brand-autodark pe-0 pe-md-3 m-0">
            <a href="{{ url('/') }}" class="d-flex align-items-center text-reset text-decoration-none">
                <span class="brand-mark me-2">M</span>
                <span class="d-flex flex-column lh-1">
                    <span class="fw-bold">Manajemen</span>
                    <span class="text-secondary" style="font-size:.7rem;letter-spacing:.04em">FEB UNM</span>
                </span>
            </a>
        </h1>

        <div class="navbar-nav flex-row order-md-last ms-md-3 d-none d-md-flex">
            <a href="{{ route('page.hubungi-kami') }}" class="btn btn-gold btn-sm"><i class="ti ti-send me-1"></i>Hubungi Kami</a>
        </div>

        <div class="collapse navbar-collapse" id="navbar-menu">
            <ul class="navbar-nav ms-auto">
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
        </div>
    </div>
</header>
