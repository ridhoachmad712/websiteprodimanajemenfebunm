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
            <div class="row g-4">
                {{-- Kalender (kiri) — disembunyikan di mobile --}}
                <div class="col-lg-8 d-none d-lg-block">
                    <div class="card">
                        <div class="card-body">
                            <div id="kalender"></div>
                        </div>
                    </div>
                </div>

                {{-- Daftar agenda (kanan) — mengikuti bulan aktif; di mobile jadi satu-satunya --}}
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h3 class="card-title m-0"><i class="ti ti-list me-2"></i><span id="daftarBulan">Agenda</span></h3>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-secondary" id="daftarPrev" aria-label="Bulan sebelumnya"><i class="ti ti-chevron-left"></i></button>
                                <button type="button" class="btn btn-outline-secondary" id="daftarNext" aria-label="Bulan berikutnya"><i class="ti ti-chevron-right"></i></button>
                            </div>
                        </div>
                        <div class="list-group list-group-flush kalender-daftar" id="daftarWrap">
                            <div class="list-group-item text-secondary text-center py-4">Memuat…</div>
                        </div>
                    </div>
                </div>
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

@push('styles')
<style>
    /* Hari Minggu berwarna merah (angka tanggal & header kolom) */
    .fc .fc-day-sun .fc-daygrid-day-number,
    .fc .fc-col-header-cell.fc-day-sun .fc-col-header-cell-cushion { color: #d63939; }
    /* Judul kegiatan tampil penuh (tidak terpotong) */
    .fc-daygrid-event { white-space: normal !important; }
    .fc-daygrid-event .fc-event-title { white-space: normal; overflow: visible; }
    .fc-daygrid-day-events { overflow: visible; }
    .fc-event { cursor: pointer; }
    /* Daftar agenda bisa di-scroll & selaras tinggi kalender (desktop) */
    .min-w-0 { min-width: 0; }
    @media (min-width: 992px) { .kalender-daftar { max-height: 640px; overflow-y: auto; } }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var EVENTS = @json($events);

        function escapeHtml(s) {
            return String(s).replace(/[&<>"']/g, function (c) {
                return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
            });
        }

        var fmtHari = new Intl.DateTimeFormat('id-ID', { weekday: 'long', day: 'numeric', month: 'short', year: 'numeric' });
        var fmtJam = new Intl.DateTimeFormat('id-ID', { hour: '2-digit', minute: '2-digit' });
        var fmtBulan = new Intl.DateTimeFormat('id-ID', { month: 'long', year: 'numeric' });

        // Render daftar untuk bulan tertentu (monthDate = tanggal mana pun di bulan itu).
        function renderListForMonth(monthDate) {
            var mStart = new Date(monthDate.getFullYear(), monthDate.getMonth(), 1);
            var mEnd = new Date(monthDate.getFullYear(), monthDate.getMonth() + 1, 1);
            document.getElementById('daftarBulan').textContent = fmtBulan.format(mStart);

            var items = EVENTS.filter(function (e) {
                if (!e.start) return false;
                var s = new Date(e.start);
                var eEnd = e.end ? new Date(e.end) : new Date(s.getTime() + 86400000);
                return s < mEnd && eEnd > mStart;
            }).sort(function (a, b) { return new Date(a.start) - new Date(b.start); });

            var wrap = document.getElementById('daftarWrap');
            if (!items.length) {
                wrap.innerHTML = '<div class="list-group-item text-secondary text-center py-4">Tidak ada agenda di bulan ini.</div>';
                return;
            }
            wrap.innerHTML = items.map(function (e) {
                var s = new Date(e.start);
                var waktu = fmtHari.format(s);
                if (!e.allDay) waktu += ' · ' + fmtJam.format(s);
                if (e.end) {
                    var endReal = e.allDay ? new Date(new Date(e.end).getTime() - 86400000) : new Date(e.end);
                    if (endReal.toDateString() !== s.toDateString()) waktu += ' – ' + fmtHari.format(endReal);
                }
                var ext = e.extendedProps || {};
                var lokasi = ext.lokasi ? '<br><i class="ti ti-map-pin me-1"></i>' + escapeHtml(ext.lokasi) : '';
                var libur = ext.libur ? ' <span class="badge bg-red-lt ms-1">Libur</span>' : '';
                return '<div class="list-group-item"><div class="fw-semibold">' + escapeHtml(e.title) +
                    '</div><div class="text-secondary small"><i class="ti ti-calendar-event me-1"></i>' + waktu + lokasi + libur + '</div></div>';
            }).join('');
        }

        var el = document.getElementById('kalender');
        var calendar = null;
        var fallbackMonth = new Date(); fallbackMonth.setDate(1);

        if (el && window.FullCalendar) {
            calendar = new FullCalendar.Calendar(el, {
                initialView: 'dayGridMonth',
                locale: 'id',
                firstDay: 0,
                height: 'auto',
                eventDisplay: 'block',
                dayMaxEvents: false,
                headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,listMonth' },
                buttonText: { today: 'Hari ini', month: 'Bulan', list: 'Daftar' },
                noEventsText: 'Tidak ada kegiatan',
                events: EVENTS,
                datesSet: function (info) { renderListForMonth(info.view.currentStart); },
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
            calendar.render(); // memicu datesSet → daftar terisi
        } else {
            renderListForMonth(fallbackMonth);
        }

        // Navigasi bulan dari panel daftar (berfungsi juga di mobile saat kalender disembunyikan).
        document.getElementById('daftarPrev').addEventListener('click', function () {
            if (calendar) { calendar.prev(); } else { fallbackMonth.setMonth(fallbackMonth.getMonth() - 1); renderListForMonth(fallbackMonth); }
        });
        document.getElementById('daftarNext').addEventListener('click', function () {
            if (calendar) { calendar.next(); } else { fallbackMonth.setMonth(fallbackMonth.getMonth() + 1); renderListForMonth(fallbackMonth); }
        });
    });
</script>
@endpush
