@extends('layouts.frontend')

@section('title', $post->judul)
@section('meta_description', $post->excerpt ? Str::limit(strip_tags($post->excerpt), 155) : Str::limit(strip_tags($post->konten), 155))

@section('content')
    @include('frontend.partials.page-hero', [
        'title'  => $post->judul,
        'variant' => 'article',
        'crumbs' => ['Beranda' => url('/'), 'Berita' => route('post.index'), 'Artikel' => null],
    ])

    <section class="section">
        <div class="container-xl">
            <article class="article-shell">
                    <div class="mb-2">
                        @foreach ($post->categories as $c)
                            <a href="{{ route('post.category', $c) }}" class="badge bg-primary-lt text-decoration-none">{{ $c->nama }}</a>
                        @endforeach
                    </div>
                    <div class="article-meta text-secondary">
                        <span><i class="ti ti-user" aria-hidden="true"></i>{{ $post->user->name }}</span>
                        <span><i class="ti ti-calendar" aria-hidden="true"></i><time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->translatedFormat('d F Y') }}</time></span>
                        <span><i class="ti ti-eye" aria-hidden="true"></i>{{ number_format($post->dilihat, 0, ',', '.') }} dilihat</span>
                    </div>

                    @if ($post->featured_image)
                        <img src="{{ Storage::url($post->featured_image) }}" class="rounded mb-4 img-fluid w-100" alt="{{ $post->judul }}" loading="lazy">
                    @endif

                    {{-- Konten HTML dari editor (dibuat oleh admin tepercaya) --}}
                    <div class="markdown">
                        {!! $post->konten !!}
                    </div>

                    @include('frontend.partials.share', ['url' => $post->url(), 'judul' => $post->judul])

                    <hr class="my-4">
                    <a href="{{ route('post.index') }}" class="btn btn-link px-0"><i class="ti ti-arrow-left me-1"></i> Semua berita</a>
            </article>

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
