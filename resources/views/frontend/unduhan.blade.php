@extends('layouts.frontend')
@section('title', 'Pusat Unduhan')
@section('meta_description', 'Dokumen dan panduan akademik Program Studi Manajemen FEB UNM.')
@section('content')
    @include('frontend.partials.page-hero', ['title' => 'Pusat Unduhan', 'subtitle' => 'RPS, formulir, surat keputusan, dan panduan akademik.', 'crumbs' => ['Beranda' => url('/'), 'Pusat Unduhan' => null]])
    <section class="section"><div class="container-xl" data-local-collection>
        @include('frontend.partials.collection-filter', ['searchLabel' => 'Cari dokumen', 'filterCategories' => $grup->keys()->mapWithKeys(fn ($key) => [$key => $key])])
        @foreach ($grup as $kategori => $items)
            <div data-collection-group id="{{ Str::slug($kategori) }}">
                @foreach ($items as $d)
                    <div class="modern-document" data-collection-item data-category="{{ $kategori }}">
                        <i class="ti ti-file-text" aria-hidden="true"></i>
                        <div><span class="eyebrow">{{ $kategori }}</span><h2>{{ $d->judul }}</h2>@if ($d->deskripsi)<p>{{ $d->deskripsi }}</p>@endif</div>
                        @if ($d->tautan())<a class="btn btn-outline-primary" href="{{ $d->tautan() }}" target="_blank" rel="noopener"><i class="ti {{ $d->ekstensi() ? 'ti-download' : 'ti-external-link' }} me-2" aria-hidden="true"></i>{{ $d->ekstensi() ? 'Unduh '.$d->ekstensi() : 'Buka dokumen' }}</a>@else<span class="text-secondary small">File belum tersedia</span>@endif
                    </div>
                @endforeach
            </div>
        @endforeach
    </div></section>
@endsection
