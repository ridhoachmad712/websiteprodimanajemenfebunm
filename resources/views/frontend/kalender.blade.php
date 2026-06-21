@extends('layouts.frontend')

@section('title', 'Kalender Akademik')
@section('meta_description', 'Kalender akademik & agenda kegiatan Program Studi Manajemen FEB UNM.')

@section('content')
    @include('frontend.partials.page-hero', [
        'title'    => 'Kalender Akademik',
        'subtitle' => 'Jadwal kegiatan dan agenda akademik Program Studi Manajemen FEB UNM.',
        'crumbs'   => ['Beranda' => url('/'), 'Kalender Akademik' => null],
    ])

    <section class="section">
        <div class="container-xl">
            <div class="card">
                <div class="card-body">
                    <div id="kalender"></div>
                </div>
            </div>
            <div class="text-center mt-3">
                <a href="{{ route('agenda') }}" class="btn btn-outline-primary"><i class="ti ti-list me-1"></i> Lihat dalam bentuk Agenda</a>
            </div>
        </div>
    </section>

    {{-- Pemicu modal tersembunyi (dibuka lewat data-API, bukan JS bootstrap global) --}}
    <button type="button" id="kmTrigger" class="d-none" data-bs-toggle="modal" data-bs-target="#kegiatanModal"></button>

    {{-- Modal detail kegiatan --}}
    <div class="modal modal-blur fade" id="kegiatanModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kmTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2"><i class="ti ti-clock me-1 text-secondary"></i><span id="kmWaktu"></span></p>
                    <p class="mb-2 d-none" id="kmLokasiWrap"><i class="ti ti-map-pin me-1 text-secondary"></i><span id="kmLokasi"></span></p>
                    <p class="mb-0 text-secondary" id="kmDeskripsi"></p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var el = document.getElementById('kalender');
        if (!el || !window.FullCalendar) return;

        var calendar = new FullCalendar.Calendar(el, {
            initialView: 'dayGridMonth',
            locale: 'id',
            firstDay: 1,
            height: 'auto',
            headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,listMonth' },
            buttonText: { today: 'Hari ini', month: 'Bulan', list: 'Daftar' },
            noEventsText: 'Tidak ada kegiatan',
            events: @json($events),
            eventClick: function (info) {
                info.jsEvent.preventDefault();
                var e = info.event;
                document.getElementById('kmTitle').textContent = e.title;

                var opt = { dateStyle: 'full' };
                if (!e.allDay) opt.timeStyle = 'short';
                var fmt = new Intl.DateTimeFormat('id-ID', opt);
                var waktu = fmt.format(e.start);
                if (e.end) {
                    var endDate = e.allDay ? new Date(e.end.getTime() - 86400000) : e.end;
                    waktu += ' – ' + fmt.format(endDate);
                }
                document.getElementById('kmWaktu').textContent = waktu;

                var lok = e.extendedProps.lokasi;
                document.getElementById('kmLokasiWrap').classList.toggle('d-none', !lok);
                document.getElementById('kmLokasi').textContent = lok || '';
                document.getElementById('kmDeskripsi').textContent = e.extendedProps.deskripsi || '';

                document.getElementById('kmTrigger').click();
            },
        });
        calendar.render();
    });
</script>
@endpush
