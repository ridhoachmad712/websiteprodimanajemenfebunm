@extends('layouts.frontend')

@section('title', 'Tulisan Dosen')
@section('meta_description', 'Artikel dan opini dosen Program Studi Manajemen FEB UNM.')

@section('content')
    @include('frontend.partials.page-hero', [
        'title' => 'Tulisan Dosen',
        'subtitle' => 'Artikel dan opini dari dosen Program Studi Manajemen FEB UNM.',
        'crumbs' => ['Beranda' => url('/'), 'Tulisan Dosen' => null],
    ])
    <section class="section"><div class="container-xl">
        <form action="{{ route('post.writings') }}" method="GET" class="modern-filter" role="search">
            <label>Cari tulisan<input type="search" name="q" value="{{ request('q') }}" maxlength="200" class="form-control" placeholder="Ketik judul"></label>
            <label>Jenis<select name="jenis" class="form-select"><option value="">Semua jenis</option><option value="artikel" @selected(request('jenis') === 'artikel')>Artikel</option><option value="opini" @selected(request('jenis') === 'opini')>Opini</option></select></label>
            <button type="submit" class="btn btn-primary"><i class="ti ti-search me-1" aria-hidden="true"></i>Cari</button>
            @if (request('q') || request('jenis'))<a href="{{ route('post.writings') }}" class="btn btn-outline-primary">Reset</a>@endif
        </form>
        <p class="text-secondary small">{{ $posts->total() }} tulisan</p>
        <div class="row g-4">
            @forelse ($posts as $post)
                <div class="col-md-6 col-lg-4">@include('frontend.partials.post-card')</div>
            @empty
                <div class="col-12 empty"><p class="empty-title">Belum ada tulisan</p><p class="text-secondary">Coba jenis atau kata kunci lain.</p></div>
            @endforelse
        </div>
        @if ($posts->hasPages())<div class="mt-4">{{ $posts->links() }}</div>@endif
    </div></section>
@endsection
