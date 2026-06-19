@extends('layouts.frontend')

@section('title', 'Berita & Informasi')
@section('meta_description', 'Berita, artikel, prestasi, dan pengumuman terbaru Program Studi Manajemen FEB UNM.')

@section('content')
    @include('frontend.partials.page-hero', [
        'title'    => 'Berita & Informasi',
        'subtitle' => 'Kabar, artikel, prestasi, dan pengumuman terbaru dari Prodi Manajemen FEB UNM.',
        'crumbs'   => ['Beranda' => url('/'), 'Berita' => null],
    ])

    <section class="section">
        <div class="container-xl">
            {{-- Post unggulan --}}
            @if (!empty($featured))
                <article class="card card-hover featured-post mb-5" data-reveal>
                    <div class="row g-0">
                        <div class="col-md-6">
                            <a href="{{ $featured->url() }}" class="d-block h-100">
                                @if ($featured->featured_image)
                                    <img src="{{ Storage::url($featured->featured_image) }}" class="featured-img" alt="{{ $featured->judul }}">
                                @else
                                    <div class="featured-img d-flex align-items-center justify-content-center text-white"
                                         style="background:linear-gradient(135deg,var(--brand-blue),var(--brand-navy))">
                                        <i class="ti ti-news" style="font-size:3.5rem;opacity:.7"></i>
                                    </div>
                                @endif
                            </a>
                        </div>
                        <div class="col-md-6">
                            <div class="card-body d-flex flex-column h-100 p-4 p-lg-5">
                                <div class="mb-2">
                                    <span class="badge bg-gold-lt">Terbaru</span>
                                    @foreach ($featured->categories as $c)
                                        <a href="{{ route('post.category', $c) }}" class="badge bg-primary-lt text-decoration-none">{{ $c->nama }}</a>
                                    @endforeach
                                </div>
                                <h2 class="mb-2"><a href="{{ $featured->url() }}" class="text-reset text-decoration-none">{{ $featured->judul }}</a></h2>
                                <div class="text-secondary small mb-3"><i class="ti ti-calendar me-1"></i>{{ $featured->published_at?->translatedFormat('d F Y') }}</div>
                                @if ($featured->excerpt)<p class="text-secondary">{{ Str::limit($featured->excerpt, 180) }}</p>@endif
                                <a href="{{ $featured->url() }}" class="btn btn-primary mt-auto align-self-start">Baca selengkapnya <i class="ti ti-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                </article>
            @endif

            <div class="row g-4">
                <div class="col-lg-9 order-2 order-lg-1">
                    <div class="row row-cards">
                        @forelse ($posts as $post)
                            <div class="col-md-6">
                                @include('frontend.partials.post-card')
                            </div>
                        @empty
                            @unless (!empty($featured))
                                <div class="col-12">
                                    <div class="empty">
                                        <p class="empty-title">Belum ada berita</p>
                                        <p class="empty-subtitle text-secondary">Berita akan tampil di sini setelah dipublikasikan.</p>
                                    </div>
                                </div>
                            @endunless
                        @endforelse
                    </div>

                    <div class="mt-4">{{ $posts->links() }}</div>
                </div>

                <aside class="col-lg-3 order-1 order-lg-2">
                    <div class="card">
                        <div class="card-header"><h3 class="card-title">Kategori</h3></div>
                        <div class="list-group list-group-flush">
                            @foreach ($categories as $c)
                                <a href="{{ route('post.category', $c) }}" class="list-group-item list-group-item-action">{{ $c->nama }}</a>
                            @endforeach
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
