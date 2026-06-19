@extends('layouts.frontend')

@section('title', $category->nama)
@section('meta_description', 'Arsip '.$category->nama.' — Program Studi Manajemen FEB UNM.')

@section('content')
    @include('frontend.partials.page-hero', [
        'title'  => $category->nama,
        'crumbs' => ['Beranda' => url('/'), 'Berita' => route('post.index'), $category->nama => null],
    ])

    <section class="section">
        <div class="container-xl">
            <div class="row g-4">
                <div class="col-lg-9 order-2 order-lg-1">
                    <div class="row row-cards">
                        @forelse ($posts as $post)
                            <div class="col-md-6 col-xl-4">
                                @include('frontend.partials.post-card')
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="empty">
                                    <p class="empty-title">Belum ada konten di kategori ini</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <div class="mt-4">{{ $posts->links() }}</div>
                </div>

                <aside class="col-lg-3 order-1 order-lg-2">
                    <div class="card">
                        <div class="card-header"><h3 class="card-title">Kategori</h3></div>
                        <div class="list-group list-group-flush">
                            @foreach ($categories as $c)
                                <a href="{{ route('post.category', $c) }}"
                                   class="list-group-item list-group-item-action {{ $c->id === $category->id ? 'active' : '' }}">{{ $c->nama }}</a>
                            @endforeach
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
