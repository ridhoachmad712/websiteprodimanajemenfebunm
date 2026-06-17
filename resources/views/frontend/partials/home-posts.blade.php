{{-- Section beranda berisi grid kartu berita. Variabel: $judul, $posts, $link, $alt (opsional) --}}
<section class="py-6 border-top {{ ($alt ?? false) ? 'bg-light' : '' }}">
    <div class="container-xl">
        <div class="d-flex align-items-end justify-content-between mb-4">
            <h2 class="h1 mb-0">{{ $judul }}</h2>
            <a href="{{ $link }}" class="btn btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="row row-cards">
            @foreach ($posts as $post)
                <div class="col-md-6 col-lg-4">
                    @include('frontend.partials.post-card')
                </div>
            @endforeach
        </div>
    </div>
</section>
