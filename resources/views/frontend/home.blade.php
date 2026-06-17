@extends('layouts.frontend')

@section('title', 'Beranda')

@section('content')
    {{-- Hero --}}
    <section class="py-7 border-bottom bg-light">
        <div class="container-xl">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="badge bg-primary-lt mb-3">Forever in Brotherhood</span>
                    <h1 class="display-5 fw-bold mb-3">Build, Manage, Integrate</h1>
                    <p class="fs-3 text-secondary mb-4">
                        Program Studi Manajemen, Fakultas Ekonomi dan Bisnis,
                        Universitas Negeri Makassar.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="#" class="btn btn-primary btn-lg">
                            <i class="ti ti-building-bank me-1"></i> Profil Program Studi
                        </a>
                        <a href="#" class="btn btn-outline-primary btn-lg">
                            <i class="ti ti-news me-1"></i> Informasi Terbaru
                        </a>
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

    {{-- Statistik --}}
    <section class="py-6">
        <div class="container-xl">
            <div class="row row-cards">
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm">
                        <div class="card-body text-center">
                            <div class="h1 m-0 text-primary">2000<span class="text-secondary">+</span></div>
                            <div class="text-secondary mt-1">Mahasiswa Aktif</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm">
                        <div class="card-body text-center">
                            <div class="h1 m-0 text-primary">60<span class="text-secondary">+</span></div>
                            <div class="text-secondary mt-1">Dosen &amp; Tendik</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm">
                        <div class="card-body text-center">
                            <div class="h1 m-0 text-primary">3</div>
                            <div class="text-secondary mt-1">Konsentrasi Keilmuan</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm">
                        <div class="card-body text-center">
                            <div class="h1 m-0 text-primary">1999</div>
                            <div class="text-secondary mt-1">Berdiri Sejak</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Catatan scaffolding --}}
    <section class="pb-7">
        <div class="container-xl">
            <div class="card card-stamp">
                <div class="card-stamp">
                    <div class="card-stamp-icon bg-primary"><i class="ti ti-rocket"></i></div>
                </div>
                <div class="card-body">
                    <h3 class="card-title">Halaman beranda sementara (scaffolding)</h3>
                    <p class="text-secondary mb-0">
                        Ini placeholder untuk memverifikasi layout frontend + komponen Tabler sudah
                        terpasang dengan benar. Section lengkap (berita terbaru, grid dosen, prestasi,
                        pengumuman) akan dibangun pada Tugas Beranda Lengkap.
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
