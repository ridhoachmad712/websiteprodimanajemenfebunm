@extends('layouts.frontend')

@section('title', $pengumuman->judul)
@section('meta_description', $pengumuman->ringkasan(155))

@section('content')
    @include('frontend.partials.page-hero', [
        'title' => $pengumuman->judul,
        'variant' => 'article',
        'crumbs' => ['Beranda' => url('/'), 'Pengumuman' => route('pengumuman.index'), 'Detail' => null],
    ])

    <section class="section">
        <div class="container-xl">
            <article class="article-shell">
                <div class="mb-2"><span class="badge bg-primary-lt">Pengumuman</span></div>
                <div class="article-meta text-secondary">
                    <span><i class="ti ti-calendar" aria-hidden="true"></i><time datetime="{{ $pengumuman->published_at?->toDateString() }}">{{ $pengumuman->published_at?->translatedFormat('d F Y') }}</time></span>
                </div>
                <div class="markdown">{!! $pengumuman->konten !!}</div>
                @include('frontend.partials.share', ['url' => $pengumuman->url(), 'judul' => $pengumuman->judul])
                <hr class="my-4">
                <a href="{{ route('pengumuman.index') }}" class="btn btn-link px-0"><i class="ti ti-arrow-left me-1" aria-hidden="true"></i> Semua pengumuman</a>
            </article>

            @if ($lainnya->isNotEmpty())
                <div class="row justify-content-center mt-5">
                    <div class="col-lg-10">
                        <h2 class="h3 mb-3">Pengumuman lainnya</h2>
                        <div class="row row-cards">
                            @foreach ($lainnya as $p)
                                <div class="col-md-4">@include('frontend.partials.pengumuman-card')</div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
