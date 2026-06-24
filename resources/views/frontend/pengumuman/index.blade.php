@extends('layouts.frontend')

@section('title', 'Pengumuman')
@section('meta_description', 'Pengumuman resmi Program Studi Manajemen FEB UNM.')

@section('content')
    @include('frontend.partials.page-hero', [
        'title' => 'Pengumuman',
        'subtitle' => 'Informasi dan pengumuman resmi Program Studi Manajemen FEB UNM.',
        'crumbs' => ['Beranda' => url('/'), 'Pengumuman' => null],
    ])

    <section class="section">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    @forelse ($pengumuman as $p)
                        <a href="{{ $p->url() }}" class="card card-hover mb-3 d-block text-reset text-decoration-none">
                            <div class="card-body d-flex align-items-start gap-3">
                                <div class="text-center flex-shrink-0" style="width:64px">
                                    <div class="fw-bold lh-1 text-primary" style="font-size:1.8rem">{{ $p->published_at?->translatedFormat('d') }}</div>
                                    <div class="small text-uppercase text-secondary">{{ $p->published_at?->translatedFormat('M Y') }}</div>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="h4 mb-1">{{ $p->judul }}</h3>
                                    <p class="text-secondary mb-0 excerpt-clamp">{{ $p->ringkasan(160) }}</p>
                                </div>
                                <i class="ti ti-chevron-right ms-auto text-muted flex-shrink-0 align-self-center"></i>
                            </div>
                        </a>
                    @empty
                        <div class="empty">
                            <div class="empty-icon"><i class="ti ti-speakerphone fs-1"></i></div>
                            <p class="empty-title">Belum ada pengumuman</p>
                            <p class="empty-subtitle text-secondary">Pengumuman terbaru akan ditampilkan di sini.</p>
                        </div>
                    @endforelse

                    @if ($pengumuman->hasPages())
                        <div class="mt-4">{{ $pengumuman->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
