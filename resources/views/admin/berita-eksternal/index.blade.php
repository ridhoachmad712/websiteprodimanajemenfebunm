@extends('layouts.admin')

@section('title', 'Berita Eksternal')
@section('pretitle', 'Konten')
@section('page-title', 'Berita Eksternal (Liputan Media)')

@section('page-actions')
    <a href="{{ route('admin.berita-eksternal.create') }}" class="btn btn-primary">
        <i class="ti ti-plus me-1"></i> Tambah Berita
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body border-bottom py-3">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col"><input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari judul atau media…"></div>
                <div class="col-auto">
                    <button class="btn">Cari</button>
                    @if (request('cari'))<a href="{{ route('admin.berita-eksternal.index') }}" class="btn btn-link">Reset</a>@endif
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Media</th>
                        <th class="w-1">Tanggal</th>
                        <th class="w-1"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($berita as $b)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $b->judul }}</div>
                                <a href="{{ $b->url }}" target="_blank" rel="noopener" class="text-secondary small text-truncate d-inline-block" style="max-width:360px"><i class="ti ti-external-link me-1"></i>{{ $b->url }}</a>
                                @if ($b->status === 'draft')<span class="badge bg-yellow-lt ms-1">Draft</span>@endif
                            </td>
                            <td class="text-secondary">{{ $b->sumber }}</td>
                            <td class="text-secondary">{{ $b->tanggal?->translatedFormat('d M Y') ?? '—' }}</td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="{{ route('admin.berita-eksternal.edit', $b) }}" class="btn btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('admin.berita-eksternal.destroy', $b) }}" onsubmit="return confirm('Hapus berita ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-ghost-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-secondary py-4">Belum ada berita eksternal.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($berita->hasPages())
            <div class="card-footer d-flex align-items-center">{{ $berita->links() }}</div>
        @endif
    </div>
@endsection
