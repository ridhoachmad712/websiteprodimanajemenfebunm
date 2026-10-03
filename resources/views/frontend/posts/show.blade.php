@extends('layouts.frontend')

@section('title', $post->judul)
@section('meta_description', $post->excerpt ? Str::limit(strip_tags($post->excerpt), 155) : Str::limit(strip_tags($post->konten), 155))

@section('content')
    @include('frontend.partials.page-hero', [
        'title'  => $post->judul,
        'variant' => 'article',
        'crumbs' => ['Beranda' => url('/'), ($post->jenis === 'berita' ? 'Berita' : 'Tulisan Dosen') => ($post->jenis === 'berita' ? route('post.index') : route('post.writings')), ($post->jenis === 'berita' ? 'Artikel' : (\App\Models\Post::JENIS[$post->jenis] ?? 'Tulisan')) => null],
    ])

    <section class="section">
        <div class="container-xl">
            <article class="article-shell">
                    <div class="mb-2">
                        @if ($post->jenis !== 'berita')<span class="badge bg-primary-lt">{{ \App\Models\Post::JENIS[$post->jenis] ?? $post->jenis }}</span>@endif
                        @foreach ($post->categories as $c)
                            @if ($post->jenis === 'berita')
                                <a href="{{ route('post.category', $c) }}" class="badge bg-primary-lt text-decoration-none">{{ $c->nama }}</a>
                            @else
                                <span class="badge bg-primary-lt">{{ $c->nama }}</span>
                            @endif
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

                    {{-- Konten telah disanitasi saat disimpan. --}}
                    <div class="markdown">
                        {!! $post->konten !!}
                    </div>

                    @include('frontend.partials.share', ['url' => $post->url(), 'judul' => $post->judul])

                    <hr class="my-4">
                    <a href="{{ $post->jenis === 'berita' ? route('post.index') : route('post.writings') }}" class="btn btn-link px-0"><i class="ti ti-arrow-left me-1"></i> {{ $post->jenis === 'berita' ? 'Semua berita' : 'Semua tulisan' }}</a>
            </article>

            @if ($terkait->isNotEmpty())
                <div class="row justify-content-center mt-5">
                    <div class="col-lg-10">
                        <h2 class="h3 mb-3">{{ $post->jenis === 'berita' ? 'Berita lainnya' : 'Tulisan lainnya' }}</h2>
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
