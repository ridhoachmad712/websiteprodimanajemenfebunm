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

            <div class="card">
                <div class="card-header">
                    <div>
                        <h2 class="card-title mb-1">Daftar Jadwal Ujian</h2>
                        <div class="text-secondary small">Data diperbarui otomatis dari Google Sheet dan disimpan sementara selama 30 menit.</div>
                    </div>
                </div>
                <div class="card-body border-bottom">
                    <div class="row g-2">
                        <div class="col-md-8">
                            <div class="input-icon">
                                <span class="input-icon-addon"><i class="ti ti-search"></i></span>
                                <input type="search" class="form-control" id="jadwalSearch" placeholder="Cari nama, jenis ujian, pembimbing, atau penguji">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="jadwalFilter">
                                <option value="">Semua jenis ujian</option>
                                @foreach (collect($jadwal)->pluck('Ujian')->filter()->unique()->sort() as $ujian)
                                    <option value="{{ Str::lower($ujian) }}">{{ $ujian }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-vcenter table-mobile-md mb-0" id="jadwalTable">
                        <thead>
                            <tr>
                                @foreach ($columns as $column)
                                    <th>{{ $column }}</th>
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
                                    <td colspan="{{ count($columns) }}" class="text-center text-secondary py-5">
                                        Belum ada jadwal ujian yang dapat ditampilkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
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
        };

        if (search) search.addEventListener('input', apply);
        if (filter) filter.addEventListener('change', apply);
    })();
</script>
@endpush
