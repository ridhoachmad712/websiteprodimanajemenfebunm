@extends('layouts.admin')

@section('title', 'Pusat Unduhan')
@section('pretitle', 'Konten')
@section('page-title', 'Pusat Unduhan')

@section('page-actions')
    <a href="{{ route('admin.downloads.create') }}" class="btn btn-primary">
        <i class="ti ti-plus me-1"></i> Tambah Dokumen
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body border-bottom py-3">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col"><input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari dokumen…"></div>
                <div class="col-auto">
                    <button class="btn">Cari</button>
                    @if (request('cari'))<a href="{{ route('admin.downloads.index') }}" class="btn btn-link">Reset</a>@endif
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>Dokumen</th>
                        <th>Kategori</th>
                        <th>Jenis</th>
                        <th class="w-1">Urutan</th>
                        <th class="w-1"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($downloads as $d)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $d->judul }}</div>
                                @if ($d->status === 'draft')<span class="badge bg-yellow-lt">Draft</span>@endif
                                @if (! $d->tautan())<span class="badge bg-red-lt">Tanpa file/tautan</span>@endif
                            </td>
                            <td class="text-secondary">{{ $d->kategori }}</td>
                            <td>
                                @if ($d->ekstensi())
                                    <span class="badge bg-blue-lt">{{ $d->ekstensi() }}</span>
                                @elseif ($d->url)
                                    <span class="badge bg-azure-lt"><i class="ti ti-external-link me-1"></i>Tautan</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-secondary">{{ $d->urutan }}</td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="{{ route('admin.downloads.edit', $d) }}" class="btn btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('admin.downloads.destroy', $d) }}" onsubmit="return confirm('Hapus dokumen ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-ghost-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-secondary py-4">Belum ada dokumen.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($downloads->hasPages())
            <div class="card-footer d-flex align-items-center">{{ $downloads->links() }}</div>
        @endif
    </div>
@endsection
