@extends('layouts.frontend')

@section('title', $post->judul)
@section('meta_description', $post->excerpt ? Str::limit(strip_tags($post->excerpt), 155) : Str::limit(strip_tags($post->konten), 155))

@section('content')
    <section class="py-5 bg-light border-bottom">
        <div class="container-xl">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrows">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('post.index') }}">Berita</a></li>
                    <li class="breadcrumb-item active">{{ Str::limit($post->judul, 40) }}</li>
                </ol>
            </nav>
        </div>
    </section>

    <section class="py-5">
        <div class="container-xl">
            <div class="row justify-content-center">
                <article class="col-lg-8">
                    <div class="mb-2">
                        @foreach ($post->categories as $c)
                            <a href="{{ route('post.category', $c) }}" class="badge bg-primary-lt text-decoration-none">{{ $c->nama }}</a>
                        @endforeach
                    </div>
                    <h1 class="mb-2">{{ $post->judul }}</h1>
                    <div class="text-secondary mb-4">
                        <i class="ti ti-user me-1"></i>{{ $post->user->name }}
                        <span class="mx-2">·</span>
                        <i class="ti ti-calendar me-1"></i>{{ $post->published_at?->translatedFormat('d F Y') }}
                    </div>

                    @if ($post->featured_image)
                        <img src="{{ Storage::url($post->featured_image) }}" class="rounded mb-4 img-fluid w-100" alt="{{ $post->judul }}">
                    @endif

                    {{-- Konten HTML dari editor (dibuat oleh admin tepercaya) --}}
                    <div class="markdown">
                        {!! $post->konten !!}
                    </div>

                    <hr class="my-4">
                    <a href="{{ route('post.index') }}" class="btn btn-link px-0"><i class="ti ti-arrow-left me-1"></i> Semua berita</a>
                </article>
            </div>

            @if ($terkait->isNotEmpty())
                <div class="row justify-content-center mt-5">
                    <div class="col-lg-10">
                        <h2 class="h3 mb-3">Berita lainnya</h2>
                        <div class="row row-cards">
                            @foreach ($terkait as $post)
                                <div class="col-md-4">
                                    @include('frontend.partials.post-card')
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
