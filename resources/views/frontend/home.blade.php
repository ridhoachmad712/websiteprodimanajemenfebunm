@extends('layouts.frontend')

@section('title', 'Beranda')
@section('meta_description', 'Website resmi Program Studi Manajemen, Fakultas Ekonomi dan Bisnis, Universitas Negeri Makassar.')

@php
    $appearanceDefaults = \App\Http\Controllers\Admin\AppearanceController::defaults();
    $setting = fn ($key) => \App\Models\Setting::get($key, $appearanceDefaults[$key] ?? null);
    $heroSlides = \App\Models\HeroSlide::aktif()->orderBy('urutan')->orderBy('id')->get();
    $firstSlide = $heroSlides->first();
    $heroEnabled = $setting('hero.show') === '1';
    $heroHeight = (int) $setting('hero.height');
    $heroTitle = $firstSlide?->judul ?: $setting('hero.title');
    $heroAccent = $firstSlide ? null : $setting('hero.title_accent');
    $heroSubtitle = $firstSlide?->subjudul ?: $setting('hero.subtitle');
    $heroButton = $firstSlide?->btn_label ?: $setting('hero.btn1_label');
    $heroButtonUrl = $firstSlide?->btn_url ?: $setting('hero.btn1_url');
    $fallbackImage = $berita->first()?->featured_image;
    $configuredHeroImage = $setting('hero.bg_style') === 'image' ? $setting('hero.bg_image') : null;
    $heroImage = $firstSlide?->gambar ?: $configuredHeroImage ?: $fallbackImage;
    $heroButton2 = $firstSlide ? null : $setting('hero.btn2_label');
    $heroButton2Url = $setting('hero.btn2_url');
    $heroImageAlt = $firstSlide?->judul ?: $heroTitle ?: 'Kegiatan Program Studi Manajemen';
    $heroMark = $setting('navbar.brand_text') ?: 'Manajemen';
    $heroSubmark = $setting('navbar.brand_subtext') ?: 'FEB UNM';
    $menuLinks = collect($mainMenu ?? [])
        ->flatMap(fn ($item) => $item->activeChildren->isNotEmpty() ? $item->activeChildren : collect([$item]))
        ->filter(fn ($item) => filled($item->href) && $item->href !== '#' && trim(parse_url($item->href, PHP_URL_PATH) ?: '', '/') !== '')
        ->unique('href');
    $quickLinks = collect([
        ['title' => 'Kurikulum', 'href' => route('page.kurikulum')],
        ['title' => 'Jadwal Ujian', 'href' => route('page.jadwal-ujian')],
        ['title' => 'Daftar Dosen', 'href' => route('dosen.index')],
        ['title' => 'Unduhan', 'href' => route('unduhan.index')],
    ])->map(function ($link) use ($menuLinks) {
        return $menuLinks->first(fn ($item) => parse_url($item->href, PHP_URL_PATH) === parse_url($link['href'], PHP_URL_PATH))
            ?? (object) ($link + ['target' => '_self']);
    });
@endphp

@section('content')
    @if ($heroSlides->isNotEmpty() || $heroEnabled)
        <section class="home-hero" @if ($heroHeight > 0) style="--home-hero-min-height: {{ $heroHeight }}px" @endif aria-label="{{ $heroMark }} {{ $heroSubmark }}">
            @if ($heroSlides->isNotEmpty())
                <div id="heroCarousel" class="carousel slide">
                    @if ($heroSlides->count() > 1)
                        <div class="carousel-indicators home-hero-indicators">
                            @foreach ($heroSlides as $index => $slide)
                                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}" @class(['active' => $loop->first]) aria-label="Slide {{ $index + 1 }}" @if ($loop->first) aria-current="true" @endif></button>
                            @endforeach
                        </div>
                    @endif
                    <div class="carousel-inner">
                        @foreach ($heroSlides as $slide)
                            <article class="carousel-item @if ($loop->first) active @endif">
                                <div class="home-hero-layout">
                                    <div class="home-hero-copy">
                                        @if ($setting('hero.eyebrow'))<p class="home-eyebrow">{{ $setting('hero.eyebrow') }}</p>@endif
                                        @if ($loop->first)
                                            <h1 class="home-hero-title">{{ $slide->judul ?: $heroTitle ?: 'Program Studi Manajemen FEB UNM' }}</h1>
                                        @elseif ($slide->judul)
                                            <h2 class="home-hero-title">{{ $slide->judul }}</h2>
                                        @endif
                                        @if ($slide->subjudul)<p class="home-hero-lead">{{ $slide->subjudul }}</p>@endif
                                        @if ($slide->btn_label && $slide->btn_url && trim($slide->btn_url) !== '#')
                                            <a class="home-primary-link" href="{{ $slide->btn_url }}">{{ $slide->btn_label }} <i class="ti ti-arrow-right" aria-hidden="true"></i></a>
                                        @endif
                                        <p class="home-hero-meta"><span></span> {{ $heroMark }} · {{ $heroSubmark }}</p>
                                    </div>
                                    <div class="home-hero-image">
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($slide->gambar) }}" alt="{{ $slide->judul ?: 'Kegiatan Program Studi Manajemen' }}" fetchpriority="high">
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    @if ($heroSlides->count() > 1)
                        <div class="home-hero-controls" aria-label="Kontrol slide beranda">
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" aria-label="Slide sebelumnya"><i class="ti ti-arrow-left" aria-hidden="true"></i></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide="next" aria-label="Slide berikutnya"><i class="ti ti-arrow-right" aria-hidden="true"></i></button>
                        </div>
                    @endif
                </div>
            @else
                <div class="home-hero-layout">
                    <div class="home-hero-copy">
                        @if ($setting('hero.eyebrow'))<p class="home-eyebrow">{{ $setting('hero.eyebrow') }}</p>@endif
                        <h1 class="home-hero-title">{{ $heroTitle }} @if ($heroAccent)<span>{{ $heroAccent }}</span>@endif</h1>
                        @if ($heroSubtitle)<p class="home-hero-lead">{{ $heroSubtitle }}</p>@endif
                        @if ($heroButton || $heroButton2)
                            <div class="home-hero-actions">
                                @if ($heroButton)<a class="home-primary-link" href="{{ $heroButtonUrl ?: '/profil' }}">{{ $heroButton }} <i class="ti ti-arrow-right" aria-hidden="true"></i></a>@endif
                                @if ($heroButton2)<a class="home-secondary-link" href="{{ $heroButton2Url ?: '/berita' }}">{{ $heroButton2 }}</a>@endif
                            </div>
                        @endif
                        @if ($setting('hero.stats_show') === '1')
                            <div class="home-hero-stats" aria-label="Informasi program studi">
                                <div><strong>{{ number_format((int) ($stats['mahasiswa'] ?? 0), 0, ',', '.') }}</strong><span>Mahasiswa</span></div>
                                <div><strong>{{ number_format((int) ($stats['dosen'] ?? 0), 0, ',', '.') }}</strong><span>Dosen</span></div>
                                @if ($setting('hero.accreditation'))<div class="home-hero-accreditation"><strong>{{ $setting('statistik.konsentrasi') }}</strong><span>{{ $setting('hero.accreditation') }}</span></div>@endif
                            </div>
                        @else
                            <p class="home-hero-meta"><span></span> {{ $heroMark }} · {{ $heroSubmark }}</p>
                        @endif
                    </div>
                    <div class="home-hero-image {{ $heroImage ? '' : 'is-empty' }}">
                        @if ($heroImage)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($heroImage) }}" alt="{{ $heroImageAlt }}" fetchpriority="high">
                        @else
                            <div class="home-hero-image-placeholder" aria-hidden="true"><span>{{ $heroMark }}</span></div>
                        @endif
                    </div>
                </div>
            @endif
        </section>
    @endif

    @if ($quickLinks->isNotEmpty())
        <nav class="home-quicklinks" aria-label="Jelajahi situs">
            <div class="container-xl home-quicklinks-inner">
                <span class="home-quicklinks-label">Akses cepat</span>
                @foreach ($quickLinks as $item)
                    <a href="{{ $item->href }}" @if ($item->target === '_blank') target="_blank" rel="noopener" @endif>
                        <span>{{ $item->title }}</span><i class="ti ti-arrow-up-right" aria-hidden="true"></i>
                    </a>
                @endforeach
            </div>
        </nav>
    @endif

    @foreach (\App\Http\Controllers\Admin\HomeBuilderController::resolveBlocks() as $block)
        @continue(! ($block['enabled'] ?? true))
        @includeIf('frontend.blocks.'.($block['type'] ?? ''), ['block' => $block])
    @endforeach
@endsection
