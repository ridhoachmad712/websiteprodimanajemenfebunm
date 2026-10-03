@extends('layouts.frontend')

@section('title', 'Jadwal Ujian')
@section('meta_description', 'Jadwal ujian Program Studi Manajemen FEB UNM.')

@section('content')
    <section class="section schedule-page">
        <div class="container-xl">
            <header class="schedule-header">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-arrows">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Jadwal Ujian</li>
                    </ol>
                </nav>
                <h1>Jadwal Ujian</h1>
                <p>Jadwal ujian mahasiswa Program Studi Manajemen FEB UNM.</p>
            </header>

            @if ($error)
                <div class="alert alert-warning" role="alert">
                    <i class="ti ti-alert-triangle me-2"></i>{{ $error }}
                </div>
            @endif

            <div class="schedule-surface">
                @if ($jadwal)
                    <div class="schedule-filters">
                        <div class="input-icon">
                            <span class="input-icon-addon"><i class="ti ti-search" aria-hidden="true"></i></span>
                            <input type="search" class="form-control" id="jadwalSearch" aria-label="Cari jadwal ujian" placeholder="Cari jadwal">
                        </div>
                        <select class="form-select" id="jadwalFilter" aria-label="Jenis ujian">
                            <option value="">Semua jenis ujian</option>
                            @foreach (collect($jadwal)->pluck('row.Ujian')->filter()->unique()->sort() as $ujian)
                                <option value="{{ Str::lower($ujian) }}">{{ $ujian }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="schedule-results">
                        <p id="jadwalCount" role="status" aria-live="polite">{{ count($jadwal) }} jadwal</p>
                        <span class="schedule-source">Waktu Makassar (WITA) · Data Google Sheet diperbarui setiap 30 menit</span>
                        <button type="button" id="jadwalReset" class="schedule-reset" hidden>Hapus filter</button>
                    </div>
                @endif

                <div class="schedule-list" id="jadwalList" aria-label="Jadwal ujian mahasiswa">
                    @forelse ($jadwal as $item)
                        @php($row = $item['row'])
                        <article class="schedule-entry" data-search="{{ Str::lower(implode(' ', $row)) }}" data-ujian="{{ Str::lower($row['Ujian'] ?? '') }}">
                            <div class="schedule-entry-main">
                                <div class="schedule-when">
                                    <span class="schedule-date">{{ $row['Tanggal'] }}</span>
                                    <span class="schedule-time {{ empty($row['Waktu']) ? 'schedule-time--missing' : '' }}">{{ !empty($row['Waktu']) ? $row['Waktu'] : 'Waktu belum diisi' }}</span>
                                </div>
                                <div class="schedule-person">
                                    <h2>{{ !empty($row['Nama']) ? $row['Nama'] : 'Nama belum diisi' }}</h2>
                                    <span class="schedule-type">{{ !empty($row['Ujian']) ? $row['Ujian'] : 'Jenis ujian belum diisi' }}</span>
                                </div>
                            </div>

                            @if ($item['warnings'])
                                <div class="schedule-warnings">
                                    @foreach ($item['warnings'] as $warning)
                                        <span><i class="ti ti-alert-circle" aria-hidden="true"></i>{{ $warning }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <dl class="schedule-team" aria-label="Tim ujian {{ !empty($row['Nama']) ? $row['Nama'] : 'mahasiswa' }}">
                                @foreach (['Moderator/Sekertaris' => 'Moderator/Sekretaris', 'Pembimbing 1' => 'Pembimbing 1', 'Pembimbing 2' => 'Pembimbing 2', 'Penguji 1' => 'Penguji 1', 'Penguji 2' => 'Penguji 2'] as $role => $label)
                                    <div>
                                        <dt>{{ $label }}</dt>
                                        <dd>{{ !empty($row[$role]) ? $row[$role] : 'Belum diisi' }}</dd>
                                    </div>
                                @endforeach
                            </dl>
                        </article>
                    @empty
                        <p class="schedule-no-data">{{ $error ? 'Jadwal belum dapat ditampilkan.' : 'Belum ada jadwal ujian yang akan datang.' }}</p>
                    @endforelse
                </div>
            </div>
            <p id="jadwalEmpty" class="schedule-no-data" role="status" hidden>Tidak ada jadwal yang sesuai dengan pencarian.</p>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    (function () {
        var search = document.getElementById('jadwalSearch');
        var filter = document.getElementById('jadwalFilter');
        var rows = Array.prototype.slice.call(document.querySelectorAll('#jadwalList .schedule-entry'));
        var count = document.getElementById('jadwalCount');
        var reset = document.getElementById('jadwalReset');
        var empty = document.getElementById('jadwalEmpty');

        var apply = function () {
            var q = (search && search.value || '').trim().toLowerCase();
            var ujian = filter && filter.value || '';

            var shown = 0;
            rows.forEach(function (row) {
                var matchText = !q || row.getAttribute('data-search').indexOf(q) !== -1;
                var matchUjian = !ujian || row.getAttribute('data-ujian') === ujian;
                row.hidden = !(matchText && matchUjian);
                if (!row.hidden) shown++;
            });
            if (count) count.textContent = shown + ' jadwal';
            if (reset) reset.hidden = !q && !ujian;
            empty.hidden = !rows.length || shown > 0;
        };

        if (search) search.addEventListener('input', apply);
        if (filter) filter.addEventListener('change', apply);
        if (reset) reset.addEventListener('click', function () {
            search.value = '';
            filter.value = '';
            apply();
            search.focus();
        });
    })();
</script>
@endpush
