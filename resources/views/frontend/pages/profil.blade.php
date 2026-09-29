@extends('layouts.frontend')

@section('title', $page->title)
@section('meta_description', 'Profil Program Studi Manajemen FEB UNM: sambutan, sejarah, visi, misi, tujuan, strategi, dan kompetensi lulusan.')

@section('content')
    @include('frontend.partials.page-hero', [
        'title'    => $page->title,
        'image' => $page->og_image ? Storage::url($page->og_image) : asset('images/program-studi.jpg'),
        'imageAlt' => 'Kegiatan bersama Program Studi Manajemen FEB UNM',
        'subtitle' => 'Mengenal lebih dekat visi, sejarah, dan komitmen Program Studi Manajemen FEB UNM.',
        'crumbs'   => ['Beranda' => url('/'), $page->title => null],
    ])

    <section class="section">
        <div class="container-xl">
            <div class="row g-5">
                {{-- Anchor navigation (sticky + scrollspy) --}}
                <aside class="col-lg-3">
                    <nav class="profil-nav sticky-top" id="profilNav">
                        <label class="eyebrow mb-2 d-block" for="profilSectionSelect">Daftar Isi</label>
                        <select class="form-select profil-nav-select" id="profilSectionSelect">
                            @foreach ($page->sections as $key => $section)
                                <option value="#{{ $key }}">{{ $section['judul'] }}</option>
                            @endforeach
                        </select>
                        <div class="profil-nav-links">
                            @foreach ($page->sections as $key => $section)
                                <a href="#{{ $key }}" class="profil-nav-link {{ $loop->first ? 'active' : '' }}">{{ $section['judul'] }}</a>
                            @endforeach
                        </div>
                    </nav>
                </aside>

                <div class="col-lg-9">
                    @foreach ($page->sections as $key => $section)
                        <section id="{{ $key }}" class="profil-section mb-5">
                            <div class="d-flex align-items-center mb-3">
                                <h2 class="section-title mb-0">{{ $section['judul'] }}</h2>
                            </div>
                            @if (!empty($section['isi']))
                                <div class="markdown">{!! $section['isi'] !!}</div>
                            @else
                                <p class="text-secondary fst-italic">Konten section ini belum diisi. Silakan lengkapi melalui panel admin.</p>
                            @endif
                        </section>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    (function () {
        var links = Array.prototype.slice.call(document.querySelectorAll('#profilNav .profil-nav-link'));
        var sections = links.map(function (l) { return document.querySelector(l.getAttribute('href')); });
        var select = document.getElementById('profilSectionSelect');
        if (select) {
            if (location.hash && Array.from(select.options).some(function (option) { return option.value === location.hash; })) select.value = location.hash;
            select.addEventListener('change', function () { location.hash = select.value; });
        }
        if (!('IntersectionObserver' in window) || !links.length) return;

        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    var id = '#' + e.target.id;
                    links.forEach(function (l) { l.classList.toggle('active', l.getAttribute('href') === id); });
                    if (select) select.value = id;
                }
            });
        }, { rootMargin: '-30% 0px -60% 0px', threshold: 0 });

        sections.forEach(function (s) { if (s) io.observe(s); });
    })();
</script>
@endpush
