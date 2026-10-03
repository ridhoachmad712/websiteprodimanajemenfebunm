@extends('layouts.frontend')

@section('title', $prestasi->judul)
@section('meta_description', Str::limit($prestasi->deskripsi ?: $prestasi->judul, 155))
@if ($prestasi->gambar)
    @section('og_image', url(Storage::url($prestasi->gambar)))
@endif

@section('content')
    @include('frontend.partials.page-hero', [
        'title' => $prestasi->judul,
        'variant' => 'article',
        'crumbs' => ['Beranda' => url('/'), 'Prestasi' => route('prestasi.index'), $prestasi->judul => null],
    ])

    <section class="section">
        <div class="container-xl">
            <article class="article-shell">
                <div class="article-meta text-secondary">
                    <span><i class="ti ti-trophy" aria-hidden="true"></i>{{ \App\Models\Prestasi::kategoriOptions()[$prestasi->kategori] ?? $prestasi->kategori }}</span>
                    @if ($prestasi->tingkat)<span>{{ \App\Models\Prestasi::tingkatOptions()[$prestasi->tingkat] ?? $prestasi->tingkat }}</span>@endif
                    @if ($prestasi->tanggal)<span><i class="ti ti-calendar-event" aria-hidden="true"></i>{{ $prestasi->tanggal->translatedFormat('d F Y') }}</span>@endif
                </div>
                @if ($prestasi->gambar)
                    <img src="{{ Storage::url($prestasi->gambar) }}" alt="{{ $prestasi->judul }}" class="academic-photo mb-4">
                @endif
                @if ($prestasi->peraih)<p><strong>Peraih:</strong> {{ $prestasi->peraih }}</p>@endif
                @if ($prestasi->penyelenggara)<p><strong>Penyelenggara:</strong> {{ $prestasi->penyelenggara }}</p>@endif
                @if ($prestasi->deskripsi)<div class="markdown">{{ $prestasi->deskripsi }}</div>@endif
                <a href="{{ route('prestasi.index') }}" class="section-text-link mt-4"><i class="ti ti-arrow-left" aria-hidden="true"></i>Kembali ke Prestasi</a>
            </article>
        </div>
    </section>
@endsection
