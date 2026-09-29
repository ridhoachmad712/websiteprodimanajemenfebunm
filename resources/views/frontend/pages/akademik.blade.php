@extends('layouts.frontend')
@section('title', 'Informasi Akademik')
@section('meta_description', 'Layanan dan dokumen akademik Program Studi Manajemen FEB UNM.')
@section('content')
    @include('frontend.partials.page-hero', ['title' => 'Informasi Akademik', 'subtitle' => 'Akses layanan dan dokumen perkuliahan Program Studi Manajemen.', 'crumbs' => ['Beranda' => url('/'), 'Akademik' => null]])
    <section class="section"><div class="container-xl">
        <div class="row g-5">
            <div class="col-lg-6"><img src="{{ asset('images/program-studi.jpg') }}" alt="Kegiatan bersama Program Studi Manajemen FEB UNM" class="academic-photo" width="1560" height="1040"></div>
            <div class="col-lg-6">
                @foreach ([['page.jadwal-ujian', 'Jadwal Ujian', 'Waktu ujian, pembimbing, dan penguji.'], ['pengumuman.index', 'Pengumuman', 'Informasi perkuliahan dan administrasi.'], ['unduhan.index', 'Dokumen Akademik', 'RPS, formulir, dan panduan akademik.'], ['page.kurikulum', 'Kurikulum', 'Kurikulum Program Studi Manajemen.'], ['page.kalender-akademik', 'Kalender Akademik', 'Tanggal penting dan agenda akademik.']] as [$route, $title, $description])
                    <a href="{{ route($route) }}" class="academic-service"><div><h2>{{ $title }}</h2><p>{{ $description }}</p></div><i class="ti ti-arrow-right" aria-hidden="true"></i></a>
                @endforeach
            </div>
        </div>
    </div></section>
@endsection
