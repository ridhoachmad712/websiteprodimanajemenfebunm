@extends('layouts.frontend')

@section('title', 'Daftar Dosen')
@section('meta_description', 'Daftar dosen Program Studi Manajemen FEB UNM, dikelompokkan menurut Guru Besar, Dosen Tetap, MKDU, dan Dosen Luar Biasa.')

@section('content')
    {{-- Header --}}
    <section class="py-5 bg-light border-bottom">
        <div class="container-xl">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrows">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Daftar Dosen</li>
                </ol>
            </nav>
            <h1 class="mt-2 mb-1">Dosen &amp; Tenaga Pengajar</h1>
            <p class="text-secondary mb-0">{{ $total }} dosen di lingkungan Program Studi Manajemen FEB UNM.</p>
        </div>
    </section>

    <section class="py-5">
        <div class="container-xl">
            @forelse ($grup as $g)
                <div class="mb-5">
                    <div class="d-flex align-items-center mb-3">
                        <h2 class="h3 m-0">{{ $g['label'] }}</h2>
                        <span class="badge bg-primary-lt ms-2">{{ $g['items']->count() }}</span>
                    </div>

                    <div class="row row-cards">
                        @foreach ($g['items'] as $d)
                            <div class="col-sm-6 col-lg-4 col-xl-3">
                                <div class="card card-sm h-100">
                                    <div class="card-body text-center">
                                        @if ($d->foto)
                                            <span class="avatar avatar-xl mb-3" style="background-image: url('{{ Storage::url($d->foto) }}')"></span>
                                        @else
                                            <span class="avatar avatar-xl mb-3 bg-primary-lt">{{ Str::of($d->nama)->substr(0, 1)->upper() }}</span>
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
