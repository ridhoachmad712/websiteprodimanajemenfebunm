@extends('layouts.frontend')

@php
    $isContactPlaceholder = $page->slug === 'hubungi-kami'
        && trim(strip_tags($page->content ?? '')) === 'Konten halaman Hubungi Kami akan diisi melalui panel admin.';
@endphp
@section('title', $page->meta_title ?: $page->title)
@section('meta_description', $page->meta_description ?: ($isContactPlaceholder ? 'Kontak Program Studi Manajemen FEB UNM.' : Str::limit(strip_tags($page->content ?: $page->title), 155)))
@if ($page->og_image)
    @section('og_image', url(Storage::url($page->og_image)))
@endif

@section('content')
    @php
        $template = $page->sections['_template'] ?? ($page->sections ? 'sections' : 'content');
        $templateData = $page->sections['_data'] ?? [];
        $plainSections = $page->sections ? collect($page->sections)->reject(fn ($v, $k) => str_starts_with((string) $k, '_'))->all() : [];
    @endphp

    @if ($template !== 'landing')
        @include('frontend.partials.page-hero', [
            'title'  => $page->title,
            'crumbs' => ['Beranda' => url('/'), $page->title => null],
        ])
    @endif

    <section class="section {{ $template === 'landing' ? 'page-landing' : '' }}">
        <div class="container-xl">
            @if ($template === 'landing')
                <header class="page-landing-header">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-arrows">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $page->title }}</li>
                        </ol>
                    </nav>
                    @if (!empty($templateData['eyebrow']))<span class="eyebrow">{{ $templateData['eyebrow'] }}</span>@endif
                    <h1>{{ $templateData['hero_title'] ?: $page->title }}</h1>
                    @if (!empty($templateData['hero_subtitle']))<p class="section-subtitle">{{ $templateData['hero_subtitle'] }}</p>@endif
                    <div class="d-flex flex-wrap gap-2 mt-4">
                        @if (!empty($templateData['primary_label']) && !empty($templateData['primary_url']) && trim($templateData['primary_url']) !== '#')<a href="{{ $templateData['primary_url'] }}" class="btn btn-primary btn-lg">{{ $templateData['primary_label'] }}</a>@endif
                        @if (!empty($templateData['secondary_label']) && !empty($templateData['secondary_url']) && trim($templateData['secondary_url']) !== '#')<a href="{{ $templateData['secondary_url'] }}" class="btn btn-outline-primary btn-lg">{{ $templateData['secondary_label'] }}</a>@endif
                    </div>
                </header>
                @if (!empty($templateData['items']))
                    <div class="row g-3 mb-5">
                        @foreach ($templateData['items'] as $item)
                            <div class="col-md-6 col-lg-3"><div class="card h-100"><div class="card-body"><h3 class="h4">{{ $item['title'] }}</h3><p class="text-secondary mb-0">{{ $item['body'] }}</p></div></div></div>
                        @endforeach
                    </div>
                @endif
                @if (!empty($templateData['body']))<div class="markdown mx-auto" style="max-width:900px">{!! $templateData['body'] !!}</div>@endif
            @elseif ($template === 'documents')
                @if (!empty($templateData['intro']))<div class="markdown mb-4">{!! $templateData['intro'] !!}</div>@endif
                <div class="row row-cards">
                    @forelse (($templateData['items'] ?? []) as $item)
                        <div class="col-md-6 col-xl-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    @if (!empty($item['category']))<span class="badge bg-primary-lt mb-2">{{ $item['category'] }}</span>@endif
                                    <h3 class="h4">{{ $item['label'] }}</h3>
                                    @if (!empty($item['description']))<p class="text-secondary">{{ $item['description'] }}</p>@endif
                                    @if (!empty($item['url']) && trim($item['url']) !== '#')<a href="{{ $item['url'] }}" target="_blank" rel="noopener" class="btn btn-outline-primary mt-auto"><i class="ti ti-download me-1"></i>Buka / Unduh</a>@endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty"><p class="empty-title">Belum ada dokumen</p></div>
                    @endforelse
                </div>
            @elseif ($template === 'faq')
                @if (!empty($templateData['intro']))<div class="markdown mb-4">{!! $templateData['intro'] !!}</div>@endif
                <div class="accordion" id="faqAccordion">
                    @foreach (($templateData['items'] ?? []) as $i => $item)
                        <div class="accordion-item">
                            <h2 class="accordion-header"><button class="accordion-button {{ $i ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $i }}">{{ $item['question'] }}</button></h2>
                            <div id="faq{{ $i }}" class="accordion-collapse collapse {{ $i ? '' : 'show' }}" data-bs-parent="#faqAccordion"><div class="accordion-body markdown">{!! $item['answer'] !!}</div></div>
                        </div>
                    @endforeach
                </div>
            @elseif ($template === 'timeline')
                @if (!empty($templateData['intro']))<div class="markdown mb-4">{!! $templateData['intro'] !!}</div>@endif
                <div class="timeline">
                    @foreach (($templateData['items'] ?? []) as $item)
                        <div class="timeline-event">
                            <div class="timeline-event-icon bg-primary-lt text-primary"><i class="ti ti-point-filled"></i></div>
                            <div class="card timeline-event-card">
                                <div class="card-body">
                                    @if (!empty($item['date']))<div class="text-secondary small mb-1">{{ $item['date'] }}</div>@endif
                                    <h3 class="h4">{{ $item['title'] }}</h3>
                                    <div class="markdown">{!! $item['body'] !!}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @elseif ($template === 'embed')
                @if (!empty($templateData['intro']))<div class="markdown mb-4">{!! $templateData['intro'] !!}</div>@endif
                @if (!empty($templateData['embed_url']))
                    <div class="rounded overflow-hidden border bg-white">
                        <iframe src="{{ $templateData['embed_url'] }}" title="{{ $page->title }}" loading="lazy" allowfullscreen style="width:100%;height:{{ (int) ($templateData['height'] ?? 640) }}px;border:0;display:block"></iframe>
                    </div>
                @endif
            @elseif ($template === 'sections')
                <div class="row g-5">
                    <aside class="col-lg-3 d-none d-lg-block">
                        <nav class="profil-nav sticky-top">
                            <div class="eyebrow mb-2">Daftar Isi</div>
                            @foreach ($plainSections as $key => $section)
                                <a href="#{{ $key }}" class="profil-nav-link {{ $loop->first ? 'active' : '' }}">{{ $section['judul'] ?? Str::headline($key) }}</a>
                            @endforeach
                        </nav>
                    </aside>
                    <div class="col-lg-9">
                        @foreach ($plainSections as $key => $section)
                            <section id="{{ $key }}" class="profil-section mb-5">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="feature-icon me-3 flex-shrink-0"><span class="fw-bold">{{ $loop->iteration }}</span></span>
                                    <h2 class="section-title mb-0" style="font-size:1.6rem">{{ $section['judul'] ?? Str::headline($key) }}</h2>
                                </div>
                                <div class="markdown">{!! $section['isi'] ?? '' !!}</div>
                            </section>
                        @endforeach
                    </div>
                </div>
            @else
                @unless ($isContactPlaceholder)
                    <div class="row"><div class="col-lg-9"><div class="markdown">{!! $page->content !!}</div></div></div>
                @endunless
            @endif

            @if ($page->slug === 'hubungi-kami')
                <div class="row g-4 mt-1">
                    {{-- Info kontak --}}
                    <div class="col-lg-5">
                        @if (filled(\App\Models\Setting::get('kontak.alamat')))
                            <div class="d-flex mb-3"><span class="feature-icon me-3"><i class="ti ti-map-pin"></i></span><div><div class="fw-bold">Alamat</div><div class="text-secondary">{{ \App\Models\Setting::get('kontak.alamat') }}</div></div></div>
                        @endif
                        @if (filled(\App\Models\Setting::get('kontak.telepon')))
                            <div class="d-flex mb-3"><span class="feature-icon me-3"><i class="ti ti-phone"></i></span><div><div class="fw-bold">Telepon</div><div class="text-secondary">{{ \App\Models\Setting::get('kontak.telepon') }}</div></div></div>
                        @endif
                        @if (filled(\App\Models\Setting::get('kontak.email')))
                            <div class="d-flex mb-3"><span class="feature-icon me-3"><i class="ti ti-mail"></i></span><div><div class="fw-bold">Email</div><div class="text-secondary">{{ \App\Models\Setting::get('kontak.email') }}</div></div></div>
                        @endif
                    </div>

                    {{-- Form kontak --}}
                    <div class="col-lg-7">
                        <div class="card card-hover">
                            <div class="card-body">
                                <h2 class="h3 mb-3">Kirim Pesan</h2>

                                @if (session('contact_success'))
                                    <div class="alert alert-success">{{ session('contact_success') }}</div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                                @endif

                                <form method="POST" action="{{ route('contact.store') }}">
                                    @csrf
                                    {{-- Honeypot (disembunyikan dari manusia) --}}
                                    <div style="position:absolute;left:-9999px" aria-hidden="true">
                                        <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="contactNama" class="form-label required">Nama</label>
                                            <input id="contactNama" type="text" name="nama" value="{{ old('nama') }}" class="form-control" autocomplete="name" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="contactEmail" class="form-label required">Email</label>
                                            <input id="contactEmail" type="email" name="email" value="{{ old('email') }}" class="form-control" autocomplete="email" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="contactSubjek" class="form-label">Subjek</label>
                                        <input id="contactSubjek" type="text" name="subjek" value="{{ old('subjek') }}" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label for="contactPesan" class="form-label required">Pesan</label>
                                        <textarea id="contactPesan" name="pesan" rows="5" class="form-control" required>{{ old('pesan') }}</textarea>
                                    </div>
                                    <button class="btn btn-primary"><i class="ti ti-send me-1"></i> Kirim Pesan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
