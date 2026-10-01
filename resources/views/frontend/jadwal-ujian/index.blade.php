@extends('layouts.frontend')

@section('title', 'Jadwal Ujian')
@section('meta_description', 'Jadwal ujian Program Studi Manajemen FEB UNM.')

@section('content')
    @include('frontend.partials.page-hero', [
        'title'    => 'Jadwal Ujian',
        'subtitle' => 'Informasi jadwal ujian mahasiswa Program Studi Manajemen FEB UNM.',
        'crumbs'   => ['Beranda' => url('/'), 'Jadwal Ujian' => null],
    ])

    <section class="section">
        <div class="container-xl">
            @if ($error)
                <div class="alert alert-warning" role="alert">
                    <i class="ti ti-alert-triangle me-2"></i>{{ $error }}
                </div>
            @endif

            <div class="card schedule-surface">
                <div class="card-header">
                    <div>
                        <h2 class="card-title mb-1">Daftar Jadwal Ujian</h2>
                        <div class="text-secondary small">Menampilkan jadwal hari ini dan mendatang. Data dari Google Sheet diperbarui setiap 30 menit.</div>
                    </div>
                </div>
                @if ($jadwal)
                <div class="card-body border-bottom">
                    <div class="row g-2">
                        <div class="col-md-8">
                            <div class="input-icon">
                                <span class="input-icon-addon"><i class="ti ti-search"></i></span>
                                <input type="search" class="form-control" id="jadwalSearch" aria-label="Cari jadwal ujian" placeholder="Cari nama, jenis ujian, pembimbing, atau penguji">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="jadwalFilter" aria-label="Jenis ujian">
                                <option value="">Semua jenis ujian</option>
                                @foreach (collect($jadwal)->pluck('Ujian')->filter()->unique()->sort() as $ujian)
                                    <option value="{{ Str::lower($ujian) }}">{{ $ujian }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                @endif

                <div class="table-responsive" tabindex="0" role="region" aria-label="Jadwal ujian mahasiswa">
                    <table class="table table-vcenter table-mobile-md mb-0" id="jadwalTable">
                        <thead>
                            <tr>
                                @foreach ($columns as $column)
                                    <th scope="col">{{ $column }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($jadwal as $row)
                                <tr data-search="{{ Str::lower(implode(' ', $row)) }}" data-ujian="{{ Str::lower($row['Ujian'] ?? '') }}">
                                    @foreach ($columns as $column)
                                        <td data-label="{{ $column }}">
                                            @if ($column === 'Ujian' && !empty($row[$column]))
                                                <span class="badge bg-primary-lt">{{ $row[$column] }}</span>
                                            @else
                                                {{ $row[$column] ?? '' }}
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ max(1, count($columns)) }}" class="text-center text-secondary py-5">
                                        {{ $error ? 'Jadwal belum dapat ditampilkan.' : 'Belum ada jadwal ujian hari ini atau mendatang.' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <p id="jadwalEmpty" class="text-secondary py-4" role="status" hidden>Tidak ada jadwal yang sesuai dengan pencarian.</p>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    (function () {
        var search = document.getElementById('jadwalSearch');
        var filter = document.getElementById('jadwalFilter');
        var rows = Array.prototype.slice.call(document.querySelectorAll('#jadwalTable tbody tr[data-search]'));

        var apply = function () {
            var q = (search && search.value || '').trim().toLowerCase();
            var ujian = filter && filter.value || '';

            rows.forEach(function (row) {
                var matchText = !q || row.getAttribute('data-search').indexOf(q) !== -1;
                var matchUjian = !ujian || row.getAttribute('data-ujian') === ujian;
                row.hidden = !(matchText && matchUjian);
            });
            document.getElementById('jadwalEmpty').hidden = !rows.length || rows.some(function (row) { return !row.hidden; });
        };

        if (search) search.addEventListener('input', apply);
        if (filter) filter.addEventListener('change', apply);
    })();
</script>
@endpush
