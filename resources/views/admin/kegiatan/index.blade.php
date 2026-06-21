@extends('layouts.admin')

@section('title', 'Kegiatan')
@section('pretitle', 'Konten')
@section('page-title', 'Kalender & Agenda Kegiatan')

@section('page-actions')
    <a href="{{ route('admin.kegiatan.create') }}" class="btn btn-primary">
        <i class="ti ti-plus me-1"></i> Tambah Kegiatan
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body border-bottom py-3">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col"><input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari kegiatan…"></div>
                <div class="col-auto">
                    <button class="btn">Cari</button>
                    @if (request('cari'))<a href="{{ route('admin.kegiatan.index') }}" class="btn btn-link">Reset</a>@endif
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th class="w-1"></th>
                        <th>Kegiatan</th>
                        <th>Waktu</th>
                        <th>Lokasi</th>
                        <th class="w-1"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kegiatan as $k)
                        <tr>
                            <td><span class="badge" style="background:{{ $k->warna }}">&nbsp;</span></td>
                            <td><div class="fw-bold">{{ $k->judul }}</div></td>
                            <td class="text-secondary">
                                {{ $k->mulai->translatedFormat($k->seharian ? 'd M Y' : 'd M Y, H:i') }}
                                @if ($k->selesai)<span class="text-muted">– {{ $k->selesai->translatedFormat($k->seharian ? 'd M Y' : 'd M Y, H:i') }}</span>@endif
                            </td>
                            <td class="text-secondary">{{ $k->lokasi ?: '—' }}</td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="{{ route('admin.kegiatan.edit', $k) }}" class="btn btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('admin.kegiatan.destroy', $k) }}" onsubmit="return confirm('Hapus kegiatan ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-ghost-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-secondary py-4">Belum ada kegiatan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($kegiatan->hasPages())
            <div class="card-footer d-flex align-items-center">{{ $kegiatan->links() }}</div>
        @endif
    </div>
@endsection
