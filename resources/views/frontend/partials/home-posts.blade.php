{{-- Section beranda berisi grid kartu berita.
     Variabel: $judul, $posts, $link [label,url], $eyebrow (opsional), $tint (opsional) --}}
<section class="section {{ ($tint ?? false) ? 'section-tint' : '' }}">
    <div class="container-xl">
        @include('frontend.partials.section-header', [
            'eyebrow' => $eyebrow ?? null,
            'title'   => $judul,
            'link'    => $link,
        ])
        <div class="row row-cards">
            @foreach ($posts as $post)
                <div class="col-md-6 col-lg-4" data-reveal>
                    @include('frontend.partials.post-card')
                </div>
            @endforeach
        </div>
    </div>
</section>
