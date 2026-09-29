@extends('layouts.frontend')
@section('title', 'Berita & Kegiatan')
@section('meta_description', 'Berita dan kegiatan Program Studi Manajemen FEB UNM.')
@section('content')
    @include('frontend.partials.page-hero', ['title' => 'Berita & Kegiatan', 'subtitle' => 'Kegiatan akademik, kolaborasi, dan kabar dari Program Studi Manajemen.', 'crumbs' => ['Beranda' => url('/'), 'Berita' => null]])
    <section class="section"><div class="container-xl">
        <form class="modern-filter" method="GET" action="{{ route('post.index') }}" role="search">
            <label>Cari berita<input class="form-control" name="q" type="search" maxlength="200" value="{{ request('q') }}" placeholder="Ketik kata kunci"></label>
            <label>Kategori<select class="form-select" name="category"><option value="">Semua kategori</option>@foreach ($categories as $category)<option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->nama }}</option>@endforeach</select></label>
            <button class="btn btn-primary modern-filter-submit" type="submit" aria-label="Cari berita"><i class="ti ti-search me-2" aria-hidden="true"></i><span>Cari</span></button>
            <a class="btn btn-icon" href="{{ route('post.index') }}" aria-label="Reset pencarian" title="Reset pencarian"><i class="ti ti-filter-off" aria-hidden="true"></i></a>
        </form>
        <p class="text-secondary small">{{ $posts->total() }} berita</p>
        <div class="row g-4">
            @forelse ($posts as $post)
                <div class="col-md-6 col-lg-4">@include('frontend.partials.post-card')</div>
            @empty
                <div class="col-12 empty"><p class="empty-title">Tidak ada berita</p><p class="text-secondary">Belum ada berita atau tidak ada hasil yang sesuai dengan pencarian Anda.</p></div>
            @endforelse
        </div>
        <div class="mt-4">{{ $posts->links() }}</div>
    </div></section>
@endsection
