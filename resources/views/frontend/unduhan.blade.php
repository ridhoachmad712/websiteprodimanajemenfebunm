@extends('layouts.frontend')

@section('title', 'Pusat Unduhan')
@section('meta_description', 'Unduh dokumen, formulir, SK, RPS, dan panduan Program Studi Manajemen FEB UNM.')

@section('content')
    @include('frontend.partials.page-hero', [
        'title' => 'Pusat Unduhan',
        'subtitle' => 'Dokumen, formulir, SK, RPS, dan panduan yang dapat diunduh.',
        'crumbs' => ['Beranda' => url('/'), 'Pusat Unduhan' => null],
    ])

    <section class="section">
        <div class="container-xl">
            @if ($grup->isNotEmpty())
                {{-- Navigasi kategori --}}
                <div class="d-flex flex-wrap gap-2 mb-4">
                    @foreach ($grup as $kategori => $items)
                        <a href="#{{ Str::slug($kategori) }}" class="btn btn-sm btn-outline-primary">{{ $kategori }} <span class="badge bg-primary-lt ms-1">{{ $items->count() }}</span></a>
                    @endforeach
                </div>
            @endif

            @forelse ($grup as $kategori => $items)
                <div class="mb-4" id="{{ Str::slug($kategori) }}">
                    <h2 class="h3 mb-3"><i class="ti ti-folder me-2 text-primary"></i>{{ $kategori }}</h2>
                    <div class="row row-cards">
                        @foreach ($items as $d)
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100">
                                    <div class="card-body d-flex flex-column">
                                        <div class="d-flex align-items-start gap-2 mb-2">
                                            <span class="avatar bg-blue-lt text-blue"><i class="ti {{ $d->ekstensi() ? 'ti-file-text' : 'ti-external-link' }}"></i></span>
                                            <div class="min-w-0">
                                                <div class="fw-bold">{{ $d->judul }}</div>
                                                @if ($d->ekstensi())<span class="badge bg-blue-lt mt-1">{{ $d->ekstensi() }}</span>@endif
                                            </div>
                                        </div>
                                        @if ($d->deskripsi)<p class="text-secondary small">{{ $d->deskripsi }}</p>@endif
                                        @if ($d->tautan())
                                            <a href="{{ $d->tautan() }}" target="_blank" rel="noopener" class="btn btn-outline-primary mt-auto">
                                                <i class="ti {{ $d->ekstensi() ? 'ti-download' : 'ti-external-link' }} me-1"></i>{{ $d->ekstensi() ? 'Unduh' : 'Buka' }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="empty">
                    <div class="empty-icon"><i class="ti ti-folder-off fs-1"></i></div>
                    <p class="empty-title">Belum ada dokumen</p>
                    <p class="empty-subtitle text-secondary">Dokumen yang dapat diunduh akan ditampilkan di sini.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
