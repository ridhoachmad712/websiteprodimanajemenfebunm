@extends('layouts.frontend')

@section('title', 'Daftar Dosen')
@section('meta_description', 'Daftar dosen Program Studi Manajemen FEB UNM, dikelompokkan menurut Guru Besar, Dosen Tetap, MKDU, dan Dosen Luar Biasa.')

@section('content')
    @include('frontend.partials.page-hero', [
        'title'    => 'Dosen & Tenaga Pengajar',
        'subtitle' => $total.' dosen di lingkungan Program Studi Manajemen FEB UNM.',
        'crumbs'   => ['Beranda' => url('/'), 'Daftar Dosen' => null],
    ])

    <section class="section">
        <div class="container-xl">
            @forelse ($grup as $g)
                <div class="mb-5">
                    <div class="d-flex align-items-center mb-3">
                        <h2 class="h3 m-0">{{ $g['label'] }}</h2>
                        <span class="badge bg-primary-lt ms-2">{{ $g['items']->count() }}</span>
                    </div>

                    <div class="row row-cards">
                        @foreach ($g['items'] as $d)
                            <div class="col-sm-6 col-lg-4 col-xl-3" data-reveal>
                                <div class="card card-hover dosen-card h-100">
                                    <div class="card-body text-center">
                                        @if ($d->foto)
                                            <span class="avatar avatar-xl mb-3" style="background-image: url('{{ Storage::url($d->foto) }}')"></span>
                                        @else
                                            <span class="avatar avatar-xl mb-3" style="background:linear-gradient(135deg,var(--brand-blue),var(--brand-navy));color:#fff">{{ Str::of($d->nama)->substr(0, 1)->upper() }}</span>
                                        @endif
                                        <div class="fw-bold">
                                            <a href="{{ route('dosen.show', $d) }}" class="text-reset text-decoration-none stretched-link">{{ $d->nama }}</a>
                                        </div>
                                        @if ($d->konsentrasi)
                                            <div class="text-secondary small mt-1">{{ $d->konsentrasi }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="empty">
                    <p class="empty-title">Belum ada data dosen</p>
                    <p class="empty-subtitle text-secondary">Data dosen akan tampil di sini setelah ditambahkan melalui panel admin.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
