@extends('layouts.frontend')

@section('title', 'Agenda Kegiatan')
@section('meta_description', 'Agenda kegiatan mendatang Program Studi Manajemen FEB UNM.')

@section('content')
    @include('frontend.partials.page-hero', [
        'title'    => 'Agenda Kegiatan',
        'subtitle' => 'Daftar kegiatan dan acara mendatang Program Studi Manajemen FEB UNM.',
        'crumbs'   => ['Beranda' => url('/'), 'Agenda' => null],
    ])

    <section class="section">
        <div class="container-xl">
            <div class="row g-5">
                <div class="col-lg-8">
                    @forelse ($kegiatan as $k)
                        <article class="agenda-entry" data-reveal>
                            <time class="agenda-date" datetime="{{ $k->mulai->format('Y-m-d') }}">
                                <span>{{ $k->mulai->format('d') }}</span>
                                <span>{{ $k->mulai->translatedFormat('M') }}</span>
                            </time>
                            <div>
                                <h2>{{ $k->judul }}</h2>
                                <p class="agenda-meta">
                                    <span><i class="ti ti-clock" aria-hidden="true"></i>{{ $k->mulai->translatedFormat($k->seharian ? 'l, d F Y' : 'l, d F Y · H:i') }}@if ($k->selesai) - {{ $k->selesai->translatedFormat($k->seharian ? 'd F Y' : 'd F Y · H:i') }}@endif</span>
                                    @if ($k->lokasi)<span><i class="ti ti-map-pin" aria-hidden="true"></i>{{ $k->lokasi }}</span>@endif
                                </p>
                                @if ($k->deskripsi)<p class="agenda-description">{{ \Illuminate\Support\Str::limit($k->deskripsi, 180) }}</p>@endif
                            </div>
                        </article>
                    @empty
                        <div class="empty">
                            <p class="empty-title">Belum ada agenda mendatang</p>
                            <p class="empty-subtitle text-secondary">Kegiatan yang dijadwalkan akan tampil di sini.</p>
                        </div>
                    @endforelse
                </div>

                <aside class="col-lg-4">
                    <div class="agenda-calendar">
                        <i class="ti ti-calendar-month" aria-hidden="true"></i>
                        <h2>Kalender Akademik</h2>
                        <p>Lihat seluruh agenda dalam tampilan kalender.</p>
                        <a href="{{ route('page.kalender-akademik') }}" class="section-text-link">Buka Kalender <i class="ti ti-arrow-up-right" aria-hidden="true"></i></a>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
