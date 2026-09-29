@extends('layouts.frontend')
@section('title', 'Pengumuman')
@section('meta_description', 'Pengumuman resmi Program Studi Manajemen FEB UNM.')
@section('content')
    @include('frontend.partials.page-hero', ['title' => 'Pengumuman', 'subtitle' => 'Informasi perkuliahan dan administrasi mahasiswa.', 'crumbs' => ['Beranda' => url('/'), 'Pengumuman' => null]])
    <section class="section"><div class="container-xl">
        <form class="modern-filter" method="GET" action="{{ route('pengumuman.index') }}" role="search">
            <label>Cari pengumuman<input class="form-control" name="q" type="search" maxlength="200" value="{{ request('q') }}" placeholder="Ketik kata kunci"></label>
            <button class="btn btn-primary" type="submit"><i class="ti ti-search me-2" aria-hidden="true"></i>Cari</button>
            <a class="btn btn-icon" href="{{ route('pengumuman.index') }}" aria-label="Reset pencarian" title="Reset pencarian"><i class="ti ti-filter-off" aria-hidden="true"></i></a>
        </form>
        <p class="text-secondary small">{{ $pengumuman->total() }} pengumuman</p>
        <div class="modern-notices">
            @forelse ($pengumuman as $p)
                <a class="modern-notice" href="{{ $p->url() }}"><time datetime="{{ $p->published_at?->toDateString() }}">{{ $p->published_at?->translatedFormat('d M Y') }}</time><div><h2>{{ $p->judul }}</h2><p>{{ $p->ringkasan(160) }}</p></div><i class="ti ti-arrow-right" aria-hidden="true"></i></a>
            @empty
                <div class="empty"><p class="empty-title">Tidak ada pengumuman</p><p class="text-secondary">Belum ada pengumuman atau tidak ada hasil yang sesuai dengan pencarian Anda.</p></div>
            @endforelse
        </div>
        <div class="mt-4">{{ $pengumuman->links() }}</div>
    </div></section>
@endsection
