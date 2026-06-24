@extends('layouts.frontend')

@section('title', $pengumuman->judul)
@section('meta_description', $pengumuman->ringkasan(155))

@section('content')
    @include('frontend.partials.page-hero', [
        'title' => $pengumuman->judul,
        'crumbs' => ['Beranda' => url('/'), 'Pengumuman' => route('pengumuman.index'), $pengumuman->judul => null],
    ])

    <section class="section">
        <div class="container-xl">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-8">
                    <div class="text-secondary mb-3">
                        <i class="ti ti-calendar me-1"></i>{{ $pengumuman->published_at?->translatedFormat('l, d F Y') }}
                    </div>
                    <div class="markdown">{!! $pengumuman->konten !!}</div>

                    <div class="mt-4">
                        <a href="{{ route('pengumuman.index') }}" class="btn btn-outline-primary"><i class="ti ti-arrow-left me-1"></i>Semua Pengumuman</a>
                    </div>
                </div>

                @if ($lainnya->isNotEmpty())
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header"><h3 class="card-title">Pengumuman Lainnya</h3></div>
                            <div class="list-group list-group-flush">
                                @foreach ($lainnya as $l)
                                    <a href="{{ $l->url() }}" class="list-group-item list-group-item-action">
                                        <div class="fw-semibold">{{ $l->judul }}</div>
                                        <div class="text-secondary small">{{ $l->published_at?->translatedFormat('d M Y') }}</div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
