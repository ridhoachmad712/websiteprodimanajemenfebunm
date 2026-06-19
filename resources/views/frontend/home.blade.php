@extends('layouts.frontend')

@section('title', 'Beranda')
@section('meta_description', 'Website resmi Program Studi Manajemen, Fakultas Ekonomi dan Bisnis, Universitas Negeri Makassar.')

@section('content')
    {{-- ===== HERO ===== --}}
    <section class="hero">
        <span class="hero-blob"></span>
        <div class="container-xl">
            <div class="row align-items-center g-5">
                <div class="col-lg-7" data-reveal>
                    <span class="eyebrow">{{ $tagline }}</span>
                    <h1 class="mb-3">Build, Manage, <span class="text-accent">Integrate</span></h1>
                    <p class="hero-lead mb-4">
                        Program Studi Manajemen, Fakultas Ekonomi dan Bisnis,
                        Universitas Negeri Makassar — mencetak lulusan unggul,
                        berdaya saing global, dan berjiwa kewirausahaan.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('page.profil') }}" class="btn btn-gold btn-lg"><i class="ti ti-building-bank me-1"></i> Profil Program Studi</a>
                        <a href="{{ route('post.index') }}" class="btn btn-light-outline btn-lg"><i class="ti ti-news me-1"></i> Informasi Terbaru</a>
                    </div>
                </div>
                <div class="col-lg-5" data-reveal>
                    <div class="glass-card p-4">
                        <div class="d-flex align-items-center mb-3">
                            <span class="avatar avatar-lg bg-white text-primary me-3" style="color:var(--brand-blue)!important">M</span>
                            <div>
                                <div class="fw-bold fs-3">Manajemen FEB UNM</div>
                                <div style="color:rgba(255,255,255,.75)">Terakreditasi <strong class="text-accent">Baik Sekali</strong> — LAMEMBA</div>
                            </div>
                        </div>
                        <div class="row text-center g-0">
                            <div class="col-4 py-2">
                                <div class="stat-value" style="color:#fff"><span data-count="{{ (int) $stats['mahasiswa'] }}">0</span><span class="plus">+</span></div>
                                <div class="small" style="color:rgba(255,255,255,.7)">Mahasiswa</div>
                            </div>
                            <div class="col-4 py-2 border-start border-end" style="border-color:rgba(255,255,255,.15)!important">
                                <div class="stat-value" style="color:#fff"><span data-count="{{ (int) $stats['dosen'] }}">0</span><span class="plus">+</span></div>
                                <div class="small" style="color:rgba(255,255,255,.7)">Dosen</div>
                            </div>
                            <div class="col-4 py-2">
                                <div class="stat-value" style="color:#fff">3</div>
                                <div class="small" style="color:rgba(255,255,255,.7)">Konsentrasi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-wave">
            <svg viewBox="0 0 1440 70" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path fill="#ffffff" d="M0,40 C360,80 1080,0 1440,40 L1440,70 L0,70 Z"></path>
            </svg>
        </div>
    </section>

    {{-- ===== 3 PILAR BRAND ===== --}}
    <section class="section">
        <div class="container-xl">
            @include('frontend.partials.section-header', [
                'eyebrow' => 'Filosofi Kami', 'title' => 'Build · Manage · Integrate', 'center' => true,
                'subtitle' => 'Tiga pilar yang menjadi pijakan pembelajaran dan pengembangan diri mahasiswa.',
            ])
            <div class="row row-cards justify-content-center">
                @foreach (['Build' => ['ti-tools', 'Membangun fondasi keilmuan manajemen yang kuat dan aplikatif.'], 'Manage' => ['ti-adjustments', 'Mengelola sumber daya secara efektif, efisien, dan beretika.'], 'Integrate' => ['ti-affiliate', 'Mengintegrasikan teori, praktik, dan teknologi untuk daya saing global.']] as $i => $pilar)
                    @php([$icon, $desc] = $pilar)
                    <div class="col-md-4" data-reveal>
                        <div class="card card-hover h-100">
                            <div class="card-body p-4">
                                <span class="feature-icon {{ $loop->index === 1 ? 'gold' : '' }} mb-3"><i class="ti {{ $icon }}"></i></span>
                                <h3 class="mb-1">{{ $loop->index + 1 }}. {{ $i }}</h3>
                                <p class="text-secondary mb-0">{{ $desc }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== PENGANTAR / SAMBUTAN ===== --}}
    <section class="section section-tint">
        <div class="container-xl">
            <div class="row align-items-center g-5">
                <div class="col-lg-5" data-reveal>
                    <div class="card border-0 shadow-sm overflow-hidden">
                        <div class="ratio ratio-4x3" style="background:linear-gradient(135deg,var(--brand-blue),var(--brand-navy))">
                            <div class="d-flex align-items-center justify-content-center text-white">
                                <i class="ti ti-school" style="font-size:5rem;opacity:.85"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7" data-reveal>
                    <span class="eyebrow">Tentang Kami</span>
                    <h2 class="section-title mb-3">Pusat pendidikan manajemen berbasis kewirausahaan</h2>
                    <p class="text-secondary fs-4 mb-4">
                        Berdiri sejak 1999, Program Studi Manajemen FEB UNM berkomitmen menghasilkan
                        sarjana manajemen yang profesional, adaptif, dan berdaya saing — dengan tiga
                        konsentrasi: Keuangan, Pemasaran, dan Sumber Daya Manusia.
                    </p>
                    <div class="row g-3">
                        <div class="col-sm-6 d-flex align-items-start"><i class="ti ti-circle-check text-primary fs-2 me-2"></i><span>Akreditasi <strong>Baik Sekali</strong> (LAMEMBA)</span></div>
                        <div class="col-sm-6 d-flex align-items-start"><i class="ti ti-circle-check text-primary fs-2 me-2"></i><span>Kurikulum berbasis kewirausahaan</span></div>
                        <div class="col-sm-6 d-flex align-items-start"><i class="ti ti-circle-check text-primary fs-2 me-2"></i><span>Dosen kompeten &amp; berpengalaman</span></div>
                        <div class="col-sm-6 d-flex align-items-start"><i class="ti ti-circle-check text-primary fs-2 me-2"></i><span>Jejaring alumni yang luas</span></div>
                    </div>
                    <a href="{{ route('page.profil') }}" class="btn btn-primary mt-4">Selengkapnya tentang prodi <i class="ti ti-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== BERITA & INFORMASI ===== --}}
    @includeWhen($berita->isNotEmpty(), 'frontend.partials.home-posts', ['eyebrow' => 'Kabar Terkini', 'judul' => 'Berita & Informasi', 'posts' => $berita, 'link' => ['label' => 'Lihat Semua', 'url' => route('post.index')]])

    {{-- ===== DOSEN PREVIEW ===== --}}
    @if ($dosenPreview->isNotEmpty())
        <section class="section section-tint">
            <div class="container-xl">
                @include('frontend.partials.section-header', [
                    'eyebrow' => 'Tenaga Pengajar', 'title' => 'Dosen & Tendik',
                    'subtitle' => 'Tenaga pengajar Program Studi Manajemen FEB UNM.',
                    'link' => ['label' => 'Semua Dosen', 'url' => route('dosen.index')],
                ])
                <div class="row row-cards">
                    @foreach ($dosenPreview as $d)
                        <div class="col-6 col-md-4 col-lg-2" data-reveal>
                            <div class="card card-hover dosen-card h-100 text-center">
                                <div class="card-body">
                                    @if ($d->foto)
                                        <span class="avatar avatar-xl mb-2" style="background-image:url('{{ Storage::url($d->foto) }}')"></span>
                                    @else
                                        <span class="avatar avatar-xl mb-2" style="background:linear-gradient(135deg,var(--brand-blue),var(--brand-navy));color:#fff">{{ Str::of($d->nama)->substr(0,1)->upper() }}</span>
                                    @endif
                                    <div class="small fw-bold lh-sm">
                                        <a href="{{ route('dosen.show', $d) }}" class="text-reset text-decoration-none stretched-link">{{ Str::limit($d->nama, 26) }}</a>
                                    </div>
                                    @if ($d->konsentrasi)<div class="text-secondary" style="font-size:.75rem">{{ $d->konsentrasi }}</div>@endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===== PRESTASI ===== --}}
    @includeWhen($prestasi->isNotEmpty(), 'frontend.partials.home-posts', ['eyebrow' => 'Capaian', 'judul' => 'Prestasi', 'posts' => $prestasi, 'link' => ['label' => 'Lihat Semua', 'url' => route('post.category', 'prestasi')]])

    {{-- ===== ARTIKEL ===== --}}
    @includeWhen($artikel->isNotEmpty(), 'frontend.partials.home-posts', ['eyebrow' => 'Wawasan', 'judul' => 'Artikel', 'posts' => $artikel, 'link' => ['label' => 'Lihat Semua', 'url' => route('post.category', 'artikel')], 'tint' => true])

    {{-- ===== PENGUMUMAN (aksen gelap) ===== --}}
    @if ($pengumuman->isNotEmpty())
        <section class="section section-dark">
            <div class="container-xl">
                @include('frontend.partials.section-header', ['eyebrow' => 'Penting', 'title' => 'Pengumuman'])
                <div class="row g-3">
                    @foreach ($pengumuman as $post)
                        <div class="col-md-6" data-reveal>
                            <a href="{{ $post->url() }}" class="glass-card d-flex align-items-center p-3 text-decoration-none h-100">
                                <span class="feature-icon gold me-3 flex-shrink-0"><i class="ti ti-speakerphone"></i></span>
                                <span class="flex-fill text-white">
                                    <span class="fw-bold d-block">{{ $post->judul }}</span>
                                    <span class="small" style="color:rgba(255,255,255,.65)">{{ $post->published_at?->translatedFormat('d F Y') }}</span>
                                </span>
                                <i class="ti ti-chevron-right" style="color:rgba(255,255,255,.6)"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===== CTA BAND ===== --}}
    <section class="section">
        <div class="container-xl">
            <div class="card border-0 text-white overflow-hidden" style="background:linear-gradient(135deg,var(--brand-blue),var(--brand-navy))" data-reveal>
                <div class="card-body p-5 text-center">
                    <h2 class="text-white mb-2">Tertarik bergabung dengan Prodi Manajemen?</h2>
                    <p class="mb-4" style="color:rgba(255,255,255,.8)">Pelajari profil, kurikulum, dan layanan akademik kami lebih lanjut.</p>
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <a href="{{ route('page.profil') }}" class="btn btn-gold btn-lg">Jelajahi Program Studi</a>
                        <a href="{{ route('page.hubungi-kami') }}" class="btn btn-light-outline btn-lg">Hubungi Kami</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
