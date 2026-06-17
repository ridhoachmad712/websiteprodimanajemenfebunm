@extends('layouts.frontend')

@section('title', $category->nama)
@section('meta_description', 'Arsip '.$category->nama.' — Program Studi Manajemen FEB UNM.')

@section('content')
    <section class="py-5 bg-light border-bottom">
        <div class="container-xl">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrows">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('post.index') }}">Berita</a></li>
                    <li class="breadcrumb-item active">{{ $category->nama }}</li>
                </ol>
            </nav>
            <h1 class="mt-2 mb-0">Kategori: {{ $category->nama }}</h1>
        </div>
    </section>

    <section class="py-5">
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
