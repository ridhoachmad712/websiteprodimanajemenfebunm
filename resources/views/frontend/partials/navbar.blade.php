{{-- Identitas dan tombol admin berada di luar menu sticky. --}}
@php
    $isActive = function ($url) {
        if (! $url || $url === '#') return false;
        $parts = parse_url($url);
        if (! empty($parts['host']) && $parts['host'] !== request()->getHost()) return false;
        $path = trim($parts['path'] ?? '', '/');
        return $path === '' ? request()->is('/') : request()->is($path, $path.'/*');
    };

    $telepon = \App\Models\Setting::get('kontak.telepon');
    $email = \App\Models\Setting::get('kontak.email');
    $instagram = \App\Models\Setting::get('sosmed.instagram');
    $tiktok = \App\Models\Setting::get('sosmed.tiktok');
    $facebook = \App\Models\Setting::get('sosmed.facebook');
    $defaults = \App\Http\Controllers\Admin\AppearanceController::defaults();
    $setting = fn ($key) => \App\Models\Setting::get($key, $defaults[$key] ?? null);
    $logo = $setting('navbar.logo');
    $brandMark = $setting('navbar.brand_mark');
    $brandText = $setting('navbar.brand_text');
    $brandSubtext = $setting('navbar.brand_subtext');
    $navSticky = $setting('navbar.sticky') === '1';
    $navTopbar = $setting('navbar.show_topbar') === '1';
    $navCenter = $setting('navbar.menu_center') === '1';
    $navButtons = \App\Http\Controllers\Admin\AppearanceController::navbarButtons();
@endphp

@if ($navTopbar)
    <div class="topbar d-none d-md-block">
        <div class="container-xl d-flex justify-content-between align-items-center">
            <div class="d-flex gap-3">
                @if ($telepon)<span><i class="ti ti-phone me-1" aria-hidden="true"></i>{{ $telepon }}</span>@endif
                @if ($email)<a href="mailto:{{ $email }}" class="topbar-link"><i class="ti ti-mail me-1" aria-hidden="true"></i>{{ $email }}</a>@endif
            </div>
            <div class="d-flex gap-2 align-items-center">
                @if ($instagram)<a href="{{ $instagram }}" target="_blank" rel="noopener" class="topbar-link" aria-label="Instagram"><i class="ti ti-brand-instagram" aria-hidden="true"></i></a>@endif
                @if ($tiktok)<a href="{{ $tiktok }}" target="_blank" rel="noopener" class="topbar-link" aria-label="TikTok"><i class="ti ti-brand-tiktok" aria-hidden="true"></i></a>@endif
                @if ($facebook)<a href="{{ $facebook }}" target="_blank" rel="noopener" class="topbar-link" aria-label="Facebook"><i class="ti ti-brand-facebook" aria-hidden="true"></i></a>@endif
            </div>
        </div>
    </div>
@endif

<div class="site-header {{ request()->routeIs('home') ? 'site-header--home' : '' }}">
    <header class="site-brandbar d-print-none">
        <div class="container-xl site-navbar-inner">
            <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center text-reset text-decoration-none m-0">
                @if ($logo)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($logo) }}" alt="{{ trim($brandText.' '.$brandSubtext) }}">
                @else
                    @if ($brandMark)<span class="brand-mark me-2">{{ $brandMark }}</span>@endif
                    <span class="d-flex flex-column lh-1">
                        <span class="fw-bold">{{ $brandText }}</span>
                        @if ($brandSubtext)<span class="text-secondary site-brand-subtext">{{ $brandSubtext }}</span>@endif
                    </span>
                @endif
            </a>

            @if (count($navButtons))
                <div class="site-header-actions">
                    @foreach ($navButtons as $button)
                        <a href="{{ $button['url'] ?: '#' }}" class="btn btn-{{ $button['color'] ?? 'primary' }}">{{ $button['label'] }}</a>
                    @endforeach
                </div>
            @endif
        </div>
    </header>
</div>

    <div class="site-navmenu-shell {{ $navSticky ? 'sticky-top' : '' }} d-print-none">
        <nav class="navbar navbar-expand-xl site-navmenu">
            <div class="container-xl {{ $navCenter ? 'is-centered' : '' }}">
                <button class="navbar-toggler site-menu-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Buka menu">
                    <i class="ti ti-menu-2 fs-3" aria-hidden="true"></i><span>Menu</span>
                </button>

                <div class="collapse navbar-collapse" id="navbar-menu">
                <ul class="navbar-nav">
                    @foreach ($mainMenu as $item)
                        @if ($item->activeChildren->isNotEmpty())
                            @php($childActive = $item->activeChildren->contains(fn ($child) => $isActive($child->href)))
                            <li class="nav-item dropdown {{ $childActive ? 'active' : '' }}">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">{{ $item->title }}</a>
                                <div class="dropdown-menu">
                                    @include('frontend.partials.menu-dropdown', ['items' => $item->activeChildren])
                                </div>
                            </li>
                        @else
                            <li class="nav-item {{ $isActive($item->href) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ $item->href }}" @if ($item->target === '_blank') target="_blank" rel="noopener" @endif>{{ $item->title }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>

                </div>
                <button class="site-search-toggle btn btn-icon" type="button" data-bs-toggle="collapse" data-bs-target="#siteSearchPanel" aria-controls="siteSearchPanel" aria-expanded="false" aria-label="Cari di situs" title="Cari di situs"><i class="ti ti-search" aria-hidden="true"></i></button>
            </div>
        </nav>
        <div class="collapse" id="siteSearchPanel">
            <form class="site-search" role="search" action="{{ route('search.index') }}" method="GET">
                <label class="visually-hidden" for="siteSearch">Cari di situs</label>
                <input id="siteSearch" type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari di situs…">
                <button type="submit" class="site-search-submit btn btn-primary" aria-label="Cari"><i class="ti ti-search" aria-hidden="true"></i></button>
            </form>
        </div>
    </div>
