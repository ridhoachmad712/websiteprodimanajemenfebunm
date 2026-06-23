@extends('layouts.admin')

@section('title', 'Prestasi')
@section('pretitle', 'Konten')
@section('page-title', 'Prestasi')

@section('page-actions')
    <a href="{{ route('admin.prestasi.create') }}" class="btn btn-primary">
        <i class="ti ti-plus me-1"></i> Tambah Prestasi
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body border-bottom py-3">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col"><input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari judul atau peraih…"></div>
                <div class="col-auto">
                    <button class="btn">Cari</button>
                    @if (request('cari'))<a href="{{ route('admin.prestasi.index') }}" class="btn btn-link">Reset</a>@endif
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th class="w-1"></th>
                        <th>Prestasi</th>
                        <th>Kategori</th>
                        <th>Tingkat</th>
                        <th>Tanggal</th>
                        <th class="w-1"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($prestasi as $p)
                        @php($meta = \App\Models\Prestasi::tingkatMeta($p->tingkat))
                        <tr>
                            <td>
                                @if ($p->gambar)
                                    <img src="{{ Storage::url($p->gambar) }}" alt="" class="rounded" style="width:48px;height:48px;object-fit:cover">
                                @else
                                    <span class="avatar bg-azure-lt"><i class="ti ti-trophy"></i></span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">{{ $p->judul }}</div>
                                @if ($p->peraih)<div class="text-secondary small">{{ $p->peraih }}</div>@endif
                                @if ($p->status === 'draft')<span class="badge bg-yellow-lt">Draft</span>@endif
                            </td>
                            <td class="text-secondary">{{ \App\Models\Prestasi::kategoriOptions()[$p->kategori] ?? $p->kategori }}</td>
                            <td>
                                @if ($p->tingkat)
                                    <span class="badge bg-{{ $meta['color'] }}-lt"><i class="ti {{ $meta['icon'] }} me-1"></i>{{ \App\Models\Prestasi::tingkatOptions()[$p->tingkat] ?? $p->tingkat }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-secondary">{{ $p->tanggal?->translatedFormat('d M Y') }}</td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="{{ route('admin.prestasi.edit', $p) }}" class="btn btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('admin.prestasi.destroy', $p) }}" onsubmit="return confirm('Hapus prestasi ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-ghost-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-secondary py-4">Belum ada prestasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($prestasi->hasPages())
            <div class="card-footer d-flex align-items-center">{{ $prestasi->links() }}</div>
        @endif
    </div>
@endsection
