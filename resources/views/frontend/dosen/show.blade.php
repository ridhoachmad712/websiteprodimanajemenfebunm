@extends('layouts.frontend')

@section('title', $dosen->nama)
@section('meta_description', $dosen->nama.' — '.$dosen->kategori_label.($dosen->konsentrasi ? ', '.$dosen->konsentrasi : '').'.')

@section('content')
    @include('frontend.partials.page-hero', [
        'title'  => $dosen->nama,
        'crumbs' => ['Beranda' => url('/'), 'Daftar Dosen' => route('dosen.index'), $dosen->nama => null],
    ])

    <section class="section">
        <div class="container-xl">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            @if ($dosen->foto)
                                <img src="{{ Storage::url($dosen->foto) }}" class="rounded mb-3 img-fluid" alt="Foto {{ $dosen->nama }}">
                            @else
                                <span class="avatar avatar-2xl mb-3 bg-primary-lt" style="--tblr-avatar-size:8rem;font-size:3rem">{{ Str::of($dosen->nama)->substr(0, 1)->upper() }}</span>
                            @endif
                            <h1 class="h3 mb-1">{{ $dosen->nama }}</h1>
                            <span class="badge bg-primary-lt">{{ $dosen->kategori_label }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Informasi</h2>
                            <dl class="row">
                                <dt class="col-sm-4 text-secondary">NIP</dt>
                                <dd class="col-sm-8">{{ $dosen->nip ?: '—' }}</dd>

                                <dt class="col-sm-4 text-secondary">Kategori</dt>
                                <dd class="col-sm-8">{{ $dosen->kategori_label }}</dd>

                                <dt class="col-sm-4 text-secondary">Konsentrasi</dt>
                                <dd class="col-sm-8">{{ $dosen->konsentrasi ?: '—' }}</dd>
                            </dl>

                            @if ($dosen->bio_link)
                                <a href="{{ $dosen->bio_link }}" target="_blank" rel="noopener" class="btn btn-outline-primary">
                                    <i class="ti ti-external-link me-1"></i> Lihat profil lengkap
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('dosen.index') }}" class="btn btn-link"><i class="ti ti-arrow-left me-1"></i> Kembali ke Daftar Dosen</a>
            </div>
        </div>
    </section>
@endsection
