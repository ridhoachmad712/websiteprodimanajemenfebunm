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
    <div class="alert alert-info d-flex align-items-center">
        <i class="ti ti-arrows-move me-2"></i>
        <div>Seret ikon <i class="ti ti-grip-vertical"></i> untuk menyusun urutan tampil dosen di halaman depan. Urutan tersimpan otomatis. Pindah kategori dilakukan lewat tombol <strong>Edit</strong>.</div>
        <span id="reorderStatus" class="badge bg-green-lt ms-auto" style="display:none"><i class="ti ti-check me-1"></i>Tersimpan</span>
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
                    <tbody class="dosen-sortable">
                        @foreach ($g['items'] as $d)
                            <tr data-id="{{ $d->id }}">
                                <td class="text-secondary drag-handle" style="cursor:grab" title="Seret untuk menyusun"><i class="ti ti-grip-vertical"></i></td>
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
        <div class="card"><div class="card-body text-center text-secondary py-5">Belum ada data dosen.</div></div>
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
