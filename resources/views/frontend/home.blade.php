@extends('layouts.frontend')

@section('title', 'Beranda')
@section('meta_description', 'Website resmi Program Studi Manajemen, Fakultas Ekonomi dan Bisnis, Universitas Negeri Makassar.')

@section('content')
    {{-- ===== HERO ===== --}}
    <section class="py-7 border-bottom bg-light">
        <div class="container-xl">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="badge bg-primary-lt mb-3">{{ $tagline }}</span>
                    <h1 class="display-5 fw-bold mb-3">Build, Manage, Integrate</h1>
                    <p class="fs-3 text-secondary mb-4">
                        Program Studi Manajemen, Fakultas Ekonomi dan Bisnis,
                        Universitas Negeri Makassar.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="#" class="btn btn-primary btn-lg"><i class="ti ti-building-bank me-1"></i> Profil Program Studi</a>
                        <a href="{{ route('post.index') }}" class="btn btn-outline-primary btn-lg"><i class="ti ti-news me-1"></i> Informasi Terbaru</a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card card-md">
                        <div class="card-body text-center py-5">
                            <span class="avatar avatar-xl bg-primary text-white mb-3" style="--tblr-avatar-size:5rem;font-size:2rem">M</span>
                            <h3 class="mb-1">Manajemen FEB UNM</h3>
                            <p class="text-secondary mb-0">Terakreditasi <strong>Baik Sekali</strong> &mdash; LAMEMBA</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== 3 PILAR BRAND ===== --}}
    <section class="py-6 border-bottom">
        <div class="container-xl">
            <div class="row row-cards">
                @foreach (['Build' => ['ti-tools', 'Membangun fondasi keilmuan manajemen yang kuat dan aplikatif.'], 'Manage' => ['ti-adjustments', 'Mengelola sumber daya secara efektif, efisien, dan beretika.'], 'Integrate' => ['ti-affiliate', 'Mengintegrasikan teori, praktik, dan teknologi untuk daya saing global.']] as $pilar => [$icon, $desc])
                    <div class="col-md-4">
                        <div class="card card-sm h-100">
                            <div class="card-body">
                                <span class="avatar bg-primary-lt mb-3"><i class="ti {{ $icon }} fs-2"></i></span>
                                <h3 class="mb-1">{{ $pilar }}</h3>
                                <p class="text-secondary mb-0">{{ $desc }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== STATISTIK ===== --}}
    <section class="py-6 bg-light border-bottom">
        <div class="container-xl">
            <div class="row row-cards">
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm"><div class="card-body text-center">
                        <div class="h1 m-0 text-primary">{{ $stats['mahasiswa'] }}<span class="text-secondary">+</span></div>
                        <div class="text-secondary mt-1">Mahasiswa Aktif</div>
                    </div></div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm"><div class="card-body text-center">
                        <div class="h1 m-0 text-primary">{{ $stats['dosen'] }}<span class="text-secondary">+</span></div>
                        <div class="text-secondary mt-1">Dosen &amp; Tendik</div>
                    </div></div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm"><div class="card-body text-center">
                        <div class="h1 m-0 text-primary">3</div>
                        <div class="text-secondary mt-1">Konsentrasi Keilmuan</div>
                    </div></div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm"><div class="card-body text-center">
                        <div class="h1 m-0 text-primary">1999</div>
                        <div class="text-secondary mt-1">Berdiri Sejak</div>
                    </div></div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== BERITA & INFORMASI ===== --}}
    @includeWhen($berita->isNotEmpty(), 'frontend.partials.home-posts', ['judul' => 'Berita & Informasi', 'posts' => $berita, 'link' => route('post.index')])

    {{-- ===== DOSEN PREVIEW ===== --}}
    @if ($dosenPreview->isNotEmpty())
        <section class="py-6 bg-light border-top">
            <div class="container-xl">
                <div class="d-flex align-items-end justify-content-between mb-4">
                    <div>
                        <h2 class="h1 mb-1">Dosen &amp; Tendik</h2>
                        <p class="text-secondary mb-0">Tenaga pengajar Program Studi Manajemen.</p>
                    </div>
                    <a href="{{ route('dosen.index') }}" class="btn btn-outline-primary">Lihat Semua Dosen</a>
                </div>
                <div class="row row-cards">
                    @foreach ($dosenPreview as $d)
                        <div class="col-6 col-md-4 col-lg-2">
                            <div class="card card-sm h-100">
                                <div class="card-body text-center">
                                    @if ($d->foto)
                                        <span class="avatar avatar-lg mb-2" style="background-image:url('{{ Storage::url($d->foto) }}')"></span>
                                    @else
                                        <span class="avatar avatar-lg mb-2 bg-primary-lt">{{ Str::of($d->nama)->substr(0,1)->upper() }}</span>
                                    @endif
                                    <div class="small fw-bold lh-sm">
                                        <a href="{{ route('dosen.show', $d) }}" class="text-reset text-decoration-none stretched-link">{{ Str::limit($d->nama, 28) }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===== PRESTASI ===== --}}
    @includeWhen($prestasi->isNotEmpty(), 'frontend.partials.home-posts', ['judul' => 'Prestasi', 'posts' => $prestasi, 'link' => route('post.category', 'prestasi')])

    {{-- ===== ARTIKEL ===== --}}
    @includeWhen($artikel->isNotEmpty(), 'frontend.partials.home-posts', ['judul' => 'Artikel', 'posts' => $artikel, 'link' => route('post.category', 'artikel'), 'alt' => true])

    {{-- ===== PENGUMUMAN ===== --}}
    @if ($pengumuman->isNotEmpty())
        <section class="py-6 border-top">
            <div class="container-xl">
                <h2 class="h1 mb-4">Pengumuman</h2>
                <div class="list-group">
                    @foreach ($pengumuman as $post)
                        <a href="{{ $post->url() }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <span class="avatar bg-primary-lt me-3"><i class="ti ti-speakerphone"></i></span>
                            <span class="flex-fill">
                                <span class="fw-bold d-block">{{ $post->judul }}</span>
                                <span class="text-secondary small">{{ $post->published_at?->translatedFormat('d F Y') }}</span>
                            </span>
                            <i class="ti ti-chevron-right text-secondary"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
