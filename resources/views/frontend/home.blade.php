@extends('layouts.frontend')

@section('title', 'Beranda')
@section('meta_description', 'Website resmi Program Studi Manajemen, Fakultas Ekonomi dan Bisnis, Universitas Negeri Makassar.')

@php
    // Pengaturan tampilan hero (dengan default dari AppearanceController).
    $hd = \App\Http\Controllers\Admin\AppearanceController::defaults();
    $h  = fn ($k) => \App\Models\Setting::get($k, $hd[$k] ?? null);

    $heroShow    = $h('hero.show') === '1';
    $heroImg     = $h('hero.bg_image');
    $heroColor   = $h('hero.bg_color') ?: '#1b3a5b';
    $heroOverlay = round(((int) ($h('hero.overlay') ?: 55)) / 100, 2);

    if ($h('hero.bg_style') === 'image' && $heroImg) {
        $heroStyle = "background-image:linear-gradient(rgba(14,34,56,{$heroOverlay}),rgba(14,34,56,{$heroOverlay})),url('".Storage::url($heroImg)."');background-size:cover;background-position:center;";
    } else {
        $heroStyle = "background-image:linear-gradient(135deg, {$heroColor} 0%, color-mix(in srgb, {$heroColor}, #000 35%) 100%);";
    }

    // Tinggi hero: 0 = otomatis (padding bawaan); >0 = kunci tinggi minimum + pusatkan vertikal.
    $heroHeight = (int) ($h('hero.height') ?: 0);
    if ($heroHeight > 0) {
        $heroStyle .= "min-height:{$heroHeight}px;display:flex;align-items:center;";
    }

    $btn1 = $h('hero.btn1_label'); $btn1url = $h('hero.btn1_url') ?: '#';
    $btn2 = $h('hero.btn2_label'); $btn2url = $h('hero.btn2_url') ?: '#';
    $heroMark  = $h('navbar.brand_mark');
    $heroBrand = trim(($h('navbar.brand_text') ?? '').' '.($h('navbar.brand_subtext') ?? ''));
@endphp

@section('content')
    @php($heroSlides = \App\Models\HeroSlide::aktif()->orderBy('urutan')->orderBy('id')->get())

    {{-- ===== HERO SLIDER (bila ada slide aktif) ===== --}}
    @if ($heroSlides->isNotEmpty())
        <section class="hero-carousel-wrap">
            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="6000">
                @if ($heroSlides->count() > 1)
                    <div class="carousel-indicators">
                        @foreach ($heroSlides as $i => $s)
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $i }}" @class(['active' => $loop->first]) aria-label="Slide {{ $i + 1 }}"></button>
                        @endforeach
                    </div>
                @endif
                <div class="carousel-inner">
                    @foreach ($heroSlides as $s)
                        <div class="carousel-item @if ($loop->first) active @endif">
                            <div class="hero-slide" style="background-image:linear-gradient(rgba(14,34,56,.55),rgba(14,34,56,.65)),url('{{ Storage::url($s->gambar) }}')">
                                <div class="container-xl">
                                    <div class="hero-slide-content" data-reveal>
                                        @if ($s->judul)<h1 class="mb-3">{{ $s->judul }}</h1>@endif
                                        @if ($s->subjudul)<p class="hero-lead mb-4">{{ $s->subjudul }}</p>@endif
                                        @if ($s->btn_label)
                                            <a href="{{ $s->btn_url ?: '#' }}" class="btn btn-light btn-lg">{{ $s->btn_label }}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if ($heroSlides->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Sebelumnya</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Berikutnya</span>
                    </button>
                @endif
            </div>
        </section>
    @elseif ($heroShow)
    {{-- ===== HERO (statis, dari pengaturan Tampilan) ===== --}}
    <section class="hero" style="{{ $heroStyle }}">
        <div class="container-xl">
            <div class="row align-items-center g-4 g-lg-5">
                <div class="{{ $h('hero.stats_show') === '1' ? 'col-lg-7' : 'col-lg-9' }}" data-reveal>
                    @if ($h('hero.eyebrow'))<span class="eyebrow">{{ $h('hero.eyebrow') }}</span>@endif
                    <h1 class="mb-3">{{ $h('hero.title') }} @if ($h('hero.title_accent'))<span class="text-accent">{{ $h('hero.title_accent') }}</span>@endif</h1>
                    @if ($h('hero.subtitle'))<p class="hero-lead mb-4">{{ $h('hero.subtitle') }}</p>@endif
                    @if ($btn1 || $btn2)
                        <div class="d-flex flex-wrap gap-2">
                            @if ($btn1)<a href="{{ $btn1url }}" class="btn btn-light btn-lg">{{ $btn1 }}</a>@endif
                            @if ($btn2)<a href="{{ $btn2url }}" class="btn btn-outline-light btn-lg">{{ $btn2 }}</a>@endif
                        </div>
                    @endif
                </div>
                @if ($h('hero.stats_show') === '1')
                <div class="col-lg-5" data-reveal>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                @if ($heroMark)<span class="brand-mark me-3" style="width:2.75rem;height:2.75rem;font-size:1.25rem">{{ $heroMark }}</span>@endif
                                <div>
                                    <div class="fw-bold fs-3">{{ $heroBrand }}</div>
                                    @if ($h('hero.accreditation'))<div class="text-secondary small">{{ $h('hero.accreditation') }}</div>@endif
                                </div>
                            </div>
                            <div class="row text-center g-0">
                                <div class="col-4 py-2">
                                    <div class="stat-value"><span data-count="{{ (int) $stats['mahasiswa'] }}">0</span><span class="plus">+</span></div>
                                    <div class="small text-secondary">Mahasiswa</div>
                                </div>
                                <div class="col-4 py-2 border-start border-end">
                                    <div class="stat-value"><span data-count="{{ (int) $stats['dosen'] }}">0</span><span class="plus">+</span></div>
                                    <div class="small text-secondary">Dosen</div>
                                </div>
                                <div class="col-4 py-2">
                                    <div class="stat-value">{{ $h('statistik.konsentrasi') ?: '3' }}</div>
                                    <div class="small text-secondary">Konsentrasi</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- ===== BLOK BERANDA (dikelola via admin → Beranda) ===== --}}
    @foreach (\App\Http\Controllers\Admin\HomeBuilderController::resolveBlocks() as $block)
        @continue(! ($block['enabled'] ?? true))
        @includeIf('frontend.blocks.'.($block['type'] ?? ''), ['block' => $block])
    @endforeach
@endsection
