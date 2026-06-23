@extends('layouts.frontend')

@section('title', 'Daftar Seminar')
@section('meta_description', 'Jadwal seminar proposal, seminar hasil, dan ujian tutup mahasiswa Program Studi Manajemen FEB UNM.')

@section('content')
    @include('frontend.partials.page-hero', [
        'title' => 'Daftar Seminar',
        'subtitle' => 'Jadwal seminar proposal, seminar hasil, dan ujian tutup mahasiswa.',
        'crumbs' => ['Beranda' => url('/'), 'Daftar Seminar' => null],
    ])

    <section class="section">
        <div class="container-xl">
            {{-- Filter jenis --}}
            <div class="d-flex flex-wrap gap-2 mb-4">
                <a href="{{ route('seminar.index') }}" class="btn btn-sm {{ ! $jenisAktif ? 'btn-primary' : 'btn-outline-primary' }}">Semua</a>
                @foreach (\App\Models\Seminar::jenisOptions() as $val => $lbl)
                    <a href="{{ route('seminar.index', ['jenis' => $val]) }}" class="btn btn-sm {{ $jenisAktif === $val ? 'btn-primary' : 'btn-outline-primary' }}">{{ $lbl }}</a>
                @endforeach
            </div>

            {{-- Akan datang --}}
            <h2 class="h3 mb-3"><i class="ti ti-calendar-due me-2 text-primary"></i>Akan Datang</h2>
            @if ($mendatang->isNotEmpty())
                <div class="row row-cards mb-5">
                    @foreach ($mendatang as $s)
                        @include('frontend.partials.seminar-card', ['s' => $s, 'upcoming' => true])
                    @endforeach
                </div>
            @else
                <p class="text-secondary mb-5">Belum ada seminar terjadwal.</p>
            @endif

            {{-- Telah berlangsung --}}
            <h2 class="h3 mb-3"><i class="ti ti-history me-2 text-secondary"></i>Telah Berlangsung</h2>
            @if ($lalu->isNotEmpty())
                <div class="row row-cards">
                    @foreach ($lalu as $s)
                        @include('frontend.partials.seminar-card', ['s' => $s, 'upcoming' => false])
                    @endforeach
                </div>
                @if ($lalu->hasPages())<div class="mt-4">{{ $lalu->links() }}</div>@endif
            @else
                <p class="text-secondary">Belum ada riwayat seminar.</p>
            @endif
        </div>
    </section>
@endsection
