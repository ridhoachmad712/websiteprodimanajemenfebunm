@extends('layouts.admin')

@section('title', 'Dashboard')
@section('pretitle', 'Panel Admin')
@section('page-title', 'Dashboard')

@section('content')
    <div class="row row-deck row-cards">
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">Berita</div>
                    </div>
                    <div class="d-flex align-items-baseline">
                        <div class="h1 mb-0 me-2">{{ $stats['berita'] }}</div>
                        <span class="text-secondary">total post</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="subheader">Dosen</div>
                    <div class="d-flex align-items-baseline">
                        <div class="h1 mb-0 me-2">{{ $stats['dosen'] }}</div>
                        <span class="text-secondary">terdaftar</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="subheader">Halaman</div>
                    <div class="d-flex align-items-baseline">
                        <div class="h1 mb-0 me-2">{{ $stats['halaman'] }}</div>
                        <span class="text-secondary">statis</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="subheader">Galeri</div>
                    <div class="d-flex align-items-baseline">
                        <div class="h1 mb-0 me-2">{{ $stats['galeri'] }}</div>
                        <span class="text-secondary">media</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Selamat datang, {{ auth()->user()->name }} 👋</h3>
                    <p class="text-secondary mb-0">
                        Ini panel admin Website Prodi Manajemen FEB UNM. Modul pengelolaan
                        konten (Berita, Dosen, Halaman, Galeri, Menu, Pengaturan) akan aktif
                        bertahap. Statistik di atas masih placeholder sampai modul terkait dibangun.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
