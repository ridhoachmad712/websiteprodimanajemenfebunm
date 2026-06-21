@extends('layouts.frontend')

@section('title', 'Agenda Kegiatan')
@section('meta_description', 'Agenda kegiatan mendatang Program Studi Manajemen FEB UNM.')

@section('content')
    @include('frontend.partials.page-hero', [
        'title'    => 'Agenda Kegiatan',
        'subtitle' => 'Daftar kegiatan & acara mendatang Program Studi Manajemen FEB UNM.',
        'crumbs'   => ['Beranda' => url('/'), 'Agenda' => null],
    ])

    <section class="section">
        <div class="container-xl">
            <div class="row g-3">
                <div class="col-lg-8">
                    @forelse ($kegiatan as $k)
                        <div class="card mb-3" data-reveal>
                            <div class="card-body d-flex gap-3">
                                {{-- Tanggal --}}
                                <div class="text-center flex-shrink-0" style="width:64px">
                                    <div class="rounded text-white py-2" style="background:{{ $k->warna }}">
                                        <div class="fw-bold lh-1" style="font-size:1.5rem">{{ $k->mulai->format('d') }}</div>
                                        <div class="small text-uppercase">{{ $k->mulai->translatedFormat('M') }}</div>
                                    </div>
                                </div>
                                {{-- Isi --}}
                                <div class="flex-fill">
                                    <h3 class="mb-1">{{ $k->judul }}</h3>
                                    <div class="text-secondary small mb-2">
                                        <i class="ti ti-clock me-1"></i>{{ $k->mulai->translatedFormat($k->seharian ? 'l, d F Y' : 'l, d F Y · H:i') }}
                                        @if ($k->selesai)
                                            <span> – {{ $k->selesai->translatedFormat($k->seharian ? 'd F Y' : 'd F Y · H:i') }}</span>
                                        @endif
                                        @if ($k->lokasi)<span class="ms-2"><i class="ti ti-map-pin me-1"></i>{{ $k->lokasi }}</span>@endif
                                    </div>
                                    @if ($k->deskripsi)<p class="text-secondary mb-0">{{ \Illuminate\Support\Str::limit($k->deskripsi, 180) }}</p>@endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty">
                            <p class="empty-title">Belum ada agenda mendatang</p>
                            <p class="empty-subtitle text-secondary">Kegiatan yang dijadwalkan akan tampil di sini.</p>
                        </div>
                    @endforelse
                </div>

                <aside class="col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="ti ti-calendar-month text-primary" style="font-size:2.5rem"></i>
                            <h3 class="mt-2 mb-1">Kalender Akademik</h3>
                            <p class="text-secondary">Lihat seluruh agenda dalam tampilan kalender.</p>
                            <a href="{{ route('page.kalender-akademik') }}" class="btn btn-primary"><i class="ti ti-calendar me-1"></i> Buka Kalender</a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
