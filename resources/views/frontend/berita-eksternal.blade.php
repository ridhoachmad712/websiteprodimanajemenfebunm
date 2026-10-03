@extends('layouts.frontend')

@section('title', 'Berita Eksternal')
@section('meta_description', 'Kumpulan pemberitaan tentang Program Studi Manajemen FEB UNM di media eksternal.')

@section('content')
    @include('frontend.partials.page-hero', [
        'title' => 'Berita Eksternal',
        'subtitle' => 'Pemberitaan dan liputan tentang Program Studi Manajemen FEB UNM di berbagai media.',
        'crumbs' => ['Beranda' => url('/'), 'Berita Eksternal' => null],
    ])

    <section class="section">
        <div class="container-xl">
            <form method="GET" class="modern-filter" role="search">
                <label for="beritaEksternalCari">
                    Cari berita
                    <input id="beritaEksternalCari" type="search" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Judul atau nama media">
                </label>
                <button class="btn btn-primary" type="submit"><i class="ti ti-search me-1" aria-hidden="true"></i>Cari</button>
                @if (request('cari'))<a href="{{ route('berita-eksternal.index') }}" class="btn btn-outline-primary">Reset</a>@endif
            </form>

            <p class="text-secondary mb-0">{{ $berita->total() }} berita</p>
            <div class="external-news-list">
                @forelse ($berita as $b)
                    <article class="external-news-entry">
                        <div class="external-news-meta">
                            @if ($b->tanggal)<time datetime="{{ $b->tanggal->format('Y-m-d') }}">{{ $b->tanggal->translatedFormat('d M Y') }}</time>@endif
                            <span>{{ $b->sumber }}</span>
                        </div>
                        <h2><a href="{{ $b->url }}" target="_blank" rel="noopener">{{ $b->judul }}<i class="ti ti-external-link" aria-hidden="true"></i></a></h2>
                        @if ($b->ringkasan)<p>{{ $b->ringkasan }}</p>@endif
                    </article>
                @empty
                    <div class="empty"><p class="empty-title">Belum ada berita eksternal yang ditampilkan.</p></div>
                @endforelse
            </div>
            @if ($berita->hasPages())<div class="mt-4">{{ $berita->links() }}</div>@endif
        </div>
    </section>
@endsection
