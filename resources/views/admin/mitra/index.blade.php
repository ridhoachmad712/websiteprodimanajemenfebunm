@extends('layouts.admin')

@section('title', 'Mitra & Kerjasama')
@section('pretitle', 'Konten')
@section('page-title', 'Mitra & Kerjasama')

@section('page-actions')
    <a href="{{ route('admin.mitra.create') }}" class="btn btn-primary">
        <i class="ti ti-plus me-1"></i> Tambah Mitra
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th class="w-1">Logo</th>
                        <th>Nama</th>
                        <th>Tautan</th>
                        <th class="w-1">Urutan</th>
                        <th class="w-1">Status</th>
                        <th class="w-1"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mitra as $m)
                        <tr>
                            <td>
                                @if ($m->logo)
                                    <img src="{{ Storage::url($m->logo) }}" alt="{{ $m->nama }}" style="height:36px;width:auto;max-width:120px;object-fit:contain">
                                @else
                                    <span class="avatar bg-secondary-lt"><i class="ti ti-building"></i></span>
                                @endif
                            </td>
                            <td class="fw-bold">{{ $m->nama }}</td>
                            <td class="text-secondary text-truncate" style="max-width:240px">{{ $m->url ?: '—' }}</td>
                            <td class="text-secondary">{{ $m->urutan }}</td>
                            <td>
                                @if ($m->aktif)
                                    <span class="badge bg-green-lt">Aktif</span>
                                @else
                                    <span class="badge bg-secondary-lt">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="{{ route('admin.mitra.edit', $m) }}" class="btn btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('admin.mitra.destroy', $m) }}" onsubmit="return confirm('Hapus mitra ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-ghost-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-secondary py-4">Belum ada mitra. Tambahkan logo mitra/kerjasama untuk ditampilkan berjalan di beranda.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($mitra->hasPages())
            <div class="card-footer d-flex align-items-center">{{ $mitra->links() }}</div>
        @endif
    </div>
@endsection
