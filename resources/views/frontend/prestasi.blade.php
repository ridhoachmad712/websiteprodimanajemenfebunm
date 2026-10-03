@extends('layouts.frontend')

@section('title', 'Prestasi')
@section('meta_description', 'Capaian dan prestasi mahasiswa serta dosen Program Studi Manajemen FEB UNM.')

@section('content')
    @include('frontend.partials.page-hero', [
        'title' => 'Prestasi',
        'subtitle' => 'Capaian membanggakan mahasiswa dan dosen Program Studi Manajemen FEB UNM.',
        'crumbs' => ['Beranda' => url('/'), 'Prestasi' => null],
    ])

    <section class="section">
        <div class="container-xl">
            <form action="{{ route('prestasi.index') }}" method="GET" class="modern-filter prestasi-filter">
                <label for="prestasiKategori">Kategori
                    <select id="prestasiKategori" name="kategori" class="form-select">
                        <option value="">Semua kategori</option>
                        @foreach (\App\Models\Prestasi::kategoriOptions() as $val => $lbl)
                            <option value="{{ $val }}" @selected($kategoriAktif === $val)>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </label>
                <label for="prestasiTingkat">Tingkat
                    <select id="prestasiTingkat" name="tingkat" class="form-select">
                        <option value="">Semua tingkat</option>
                        @foreach (\App\Models\Prestasi::tingkatOptions() as $val => $lbl)
                            <option value="{{ $val }}" @selected($tingkatAktif === $val)>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </label>
                <div class="prestasi-filter-actions">
                    <button class="btn btn-primary" type="submit">Terapkan</button>
                    @if ($kategoriAktif || $tingkatAktif)<a href="{{ route('prestasi.index') }}" class="btn btn-outline-secondary">Reset</a>@endif
                </div>
            </form>

            <div class="row row-cards">
                @forelse ($prestasi as $p)
                    @php($meta = \App\Models\Prestasi::tingkatMeta($p->tingkat))
                    <div class="col-sm-6 col-lg-4">
                        <div class="card card-hover h-100">
                            @if ($p->gambar)
                                <img src="{{ Storage::url($p->gambar) }}" alt="{{ $p->judul }}" class="card-img-top" style="aspect-ratio:16/9;object-fit:cover" loading="lazy">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex flex-wrap gap-1 mb-2">
                                    @if ($p->tingkat)
                                        <span class="badge bg-{{ $meta['color'] }}-lt"><i class="ti {{ $meta['icon'] }} me-1"></i>{{ \App\Models\Prestasi::tingkatOptions()[$p->tingkat] }}</span>
                                    @endif
                                    <span class="badge bg-primary-lt">{{ \App\Models\Prestasi::kategoriOptions()[$p->kategori] ?? $p->kategori }}</span>
                                </div>
                                <h2 class="h4 mb-1"><a href="{{ route('prestasi.show', $p) }}" class="stretched-link text-reset text-decoration-none">{{ $p->judul }}</a></h2>
                                @if ($p->peraih)<div class="text-secondary mb-1"><i class="ti ti-user me-1"></i>{{ $p->peraih }}</div>@endif
                                @if ($p->deskripsi)<p class="text-secondary small excerpt-clamp mb-2">{{ $p->deskripsi }}</p>@endif
                                <div class="text-muted small mt-auto">
                                    <i class="ti ti-calendar-event me-1"></i>{{ $p->tanggal?->translatedFormat('d M Y') }}
                                    @if ($p->penyelenggara)<span class="ms-2"><i class="ti ti-building me-1"></i>{{ $p->penyelenggara }}</span>@endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty">
                            <div class="empty-icon"><i class="ti ti-trophy fs-1"></i></div>
                            <p class="empty-title">Belum ada prestasi</p>
                            <p class="empty-subtitle text-secondary">Prestasi akan ditampilkan di sini setelah ditambahkan.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if ($prestasi->hasPages())
                <div class="mt-4">{{ $prestasi->links() }}</div>
            @endif
        </div>
    </section>
@endsection
