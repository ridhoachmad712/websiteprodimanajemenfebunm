@extends('layouts.admin')

@section('title', 'Daftar Seminar')
@section('pretitle', 'Konten')
@section('page-title', 'Daftar Seminar')

@section('page-actions')
    <a href="{{ route('admin.seminar.create') }}" class="btn btn-primary">
        <i class="ti ti-plus me-1"></i> Tambah Seminar
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body border-bottom py-3">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col"><input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari nama, NIM, atau judul…"></div>
                <div class="col-auto">
                    <button class="btn">Cari</button>
                    @if (request('cari'))<a href="{{ route('admin.seminar.index') }}" class="btn btn-link">Reset</a>@endif
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Jenis</th>
                        <th>Jadwal</th>
                        <th>Tempat</th>
                        <th class="w-1"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($seminar as $s)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $s->nama }}</div>
                                @if ($s->nim)<div class="text-secondary small">NIM. {{ $s->nim }}</div>@endif
                                <div class="text-secondary small">{{ \Illuminate\Support\Str::limit($s->judul, 70) }}</div>
                                @if ($s->status === 'draft')<span class="badge bg-yellow-lt">Draft</span>@endif
                            </td>
                            <td><span class="badge bg-{{ \App\Models\Seminar::jenisColor($s->jenis) }}-lt">{{ $s->jenisLabel() }}</span></td>
                            <td class="text-secondary">{{ $s->tanggal->translatedFormat('d M Y, H:i') }}</td>
                            <td class="text-secondary">{{ $s->tempat ?: '—' }}</td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="{{ route('admin.seminar.edit', $s) }}" class="btn btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('admin.seminar.destroy', $s) }}" onsubmit="return confirm('Hapus seminar ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-ghost-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-secondary py-4">Belum ada seminar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($seminar->hasPages())
            <div class="card-footer d-flex align-items-center">{{ $seminar->links() }}</div>
        @endif
    </div>
@endsection
