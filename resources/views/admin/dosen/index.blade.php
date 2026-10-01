@extends('layouts.admin')

@section('title', 'Dosen')
@section('pretitle', 'Manajemen')
@section('page-title', 'Daftar Dosen')

@section('page-actions')
    <a href="{{ route('admin.dosen.create') }}" class="btn btn-primary">
        <i class="ti ti-plus me-1"></i> Tambah Dosen
    </a>
@endsection

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.dosen.index') }}" class="row g-3 align-items-end">
                <div class="col-12 col-lg-5">
                    <label for="dosenSearch" class="form-label">Cari dosen</label>
                    <input id="dosenSearch" type="search" name="q" value="{{ $filters['q'] }}" class="form-control" placeholder="Nama, NIP, jabatan, atau konsentrasi">
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <label for="dosenKategori" class="form-label">Kategori</label>
                    <select id="dosenKategori" name="kategori" class="form-select">
                        <option value="">Semua kategori</option>
                        @foreach ($kategori as $value => $label)
                            <option value="{{ $value }}" @selected($filters['kategori'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <label for="dosenKonsentrasi" class="form-label">Konsentrasi</label>
                    <select id="dosenKonsentrasi" name="konsentrasi" class="form-select">
                        <option value="">Semua konsentrasi</option>
                        @foreach ($konsentrasiOptions as $value)
                            <option value="{{ $value }}" @selected($filters['konsentrasi'] === $value)>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-lg-1">
                    <button type="submit" class="btn btn-primary w-100" title="Terapkan filter" aria-label="Terapkan filter"><i class="ti ti-search" aria-hidden="true"></i></button>
                </div>
            </form>
            <div class="d-flex flex-wrap align-items-center gap-3 mt-3 small text-secondary" role="status">
                <span>{{ $total }} dari {{ $totalAll }} dosen</span>
                @if ($isFiltered)
                    <a href="{{ route('admin.dosen.index') }}" class="link-primary">Reset filter</a>
                @endif
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="fw-bold">Pengelompokan konsentrasi</div>
                <div class="text-secondary small">Tampilkan subbagian konsentrasi pada daftar dosen di halaman publik.</div>
            </div>
            <form method="POST" action="{{ route('admin.dosen.grouping') }}" class="d-flex align-items-center gap-3 flex-wrap">
                @csrf @method('PUT')
                <input type="hidden" name="enabled" value="0">
                <label class="form-check form-switch m-0">
                    <input class="form-check-input" type="checkbox" name="enabled" value="1" @checked($groupByConcentration)>
                    <span class="form-check-label">Aktif</span>
                </label>
                <button type="submit" class="btn btn-outline-primary"><i class="ti ti-device-floppy me-1" aria-hidden="true"></i>Simpan</button>
            </form>
        </div>
    </div>

    <div class="alert alert-info d-flex align-items-center">
        <i class="ti {{ $isFiltered ? 'ti-filter' : 'ti-arrows-move' }} me-2" aria-hidden="true"></i>
        @if ($isFiltered)
            <div>Pengurutan dinonaktifkan saat hasil difilter. Reset filter untuk menyusun semua dosen.</div>
        @else
            <div>Seret ikon <i class="ti ti-grip-vertical" aria-hidden="true"></i> untuk menyusun urutan tampil dosen di halaman depan. Urutan tersimpan otomatis. Pindah kategori dilakukan lewat tombol <strong>Edit</strong>.</div>
            <span id="reorderStatus" class="badge bg-green-lt ms-auto" style="display:none"><i class="ti ti-check me-1" aria-hidden="true"></i>Tersimpan</span>
        @endif
    </div>

    @forelse ($grup as $key => $g)
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">{{ $g['label'] }}</h3>
                <span class="badge bg-secondary-lt ms-2">{{ $g['items']->count() }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table mb-0">
                    <thead>
                        <tr>
                            <th class="w-1"></th>
                            <th class="w-1">Foto</th>
                            <th>Nama</th>
                            <th>Konsentrasi</th>
                            <th class="w-1">NIP</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody @class(['dosen-sortable' => ! $isFiltered])>
                        @foreach ($g['items'] as $d)
                            <tr data-id="{{ $d->id }}">
                                <td class="text-secondary" @unless ($isFiltered) title="Seret untuk menyusun" @endunless>
                                    @unless ($isFiltered)<span class="drag-handle" style="cursor:grab" aria-label="Seret untuk menyusun"><i class="ti ti-grip-vertical" aria-hidden="true"></i></span>@endunless
                                </td>
                                <td>
                                    <span class="avatar" @if ($d->foto) style="background-image: url('{{ Storage::url($d->foto) }}')" @endif>
                                        @unless ($d->foto) {{ Str::of($d->nama)->substr(0, 1)->upper() }} @endunless
                                    </span>
                                </td>
                                <td><div class="fw-bold">{{ $d->nama }}</div></td>
                                <td class="text-secondary">{{ $d->konsentrasi ?: '—' }}</td>
                                <td class="text-secondary">{{ $d->nip ?: '—' }}</td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <a href="{{ route('admin.dosen.edit', $d) }}" class="btn btn-sm">Edit</a>
                                        <form method="POST" action="{{ route('admin.dosen.destroy', $d) }}"
                                              onsubmit="return confirm('Hapus data {{ $d->nama }}?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-ghost-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="card"><div class="card-body text-center text-secondary py-5">{{ $isFiltered ? 'Tidak ada dosen yang cocok dengan filter.' : 'Belum ada data dosen.' }}</div></div>
    @endforelse
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
    (function () {
        var lists = document.querySelectorAll('.dosen-sortable');
        if (!lists.length || !window.Sortable) return;

        var url = @json(route('admin.dosen.reorder'));
        var token = @json(csrf_token());
        var statusEl = document.getElementById('reorderStatus');
        var timer = null;

        function simpanUrutan() {
            // Kumpulkan seluruh ID dari semua grup sesuai urutan tampil (atas→bawah).
            var ids = [];
            document.querySelectorAll('.dosen-sortable tr[data-id]').forEach(function (tr) {
                ids.push(tr.getAttribute('data-id'));
            });

            fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                body: JSON.stringify({ ids: ids }),
            }).then(function (res) {
                if (!res.ok) throw new Error('gagal');
                if (statusEl) {
                    statusEl.style.display = '';
                    clearTimeout(timer);
                    timer = setTimeout(function () { statusEl.style.display = 'none'; }, 2000);
                }
            }).catch(function () {
                alert('Gagal menyimpan urutan. Coba lagi.');
            });
        }

        lists.forEach(function (el) {
            new Sortable(el, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'table-active',
                onEnd: simpanUrutan,
            });
        });
    })();
</script>
@endpush
