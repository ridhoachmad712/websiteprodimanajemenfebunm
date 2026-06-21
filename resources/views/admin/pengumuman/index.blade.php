@extends('layouts.admin')

@section('title', 'Pengumuman')
@section('pretitle', 'Konten')
@section('page-title', 'Pengumuman')

@section('page-actions')
    <a href="{{ route('admin.pengumuman.create') }}" class="btn btn-primary">
        <i class="ti ti-plus me-1"></i> Tambah Pengumuman
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body border-bottom py-3">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-auto">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua status</option>
                        <option value="published" @selected(request('status') === 'published')>Terbit</option>
                        <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                    </select>
                </div>
                <div class="col"><input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari judul…"></div>
                <div class="col-auto">
                    <button class="btn">Cari</button>
                    @if (request('status') || request('cari'))
                        <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-link">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th class="w-1">Status</th>
                        <th class="w-1">Terbit</th>
                        <th class="w-1"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($posts as $post)
                        <tr>
                            <td><div class="fw-bold">{{ $post->judul }}</div></td>
                            <td>
                                @if ($post->status === 'published')
                                    <span class="badge bg-green-lt">Terbit</span>
                                @else
                                    <span class="badge bg-secondary-lt">Draft</span>
                                @endif
                            </td>
                            <td class="text-secondary">{{ $post->published_at?->translatedFormat('d M Y') ?? '—' }}</td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="{{ route('admin.pengumuman.edit', $post) }}" class="btn btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('admin.pengumuman.destroy', $post) }}"
                                          onsubmit="return confirm('Hapus pengumuman ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-ghost-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-secondary py-4">Belum ada pengumuman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($posts->hasPages())
            <div class="card-footer d-flex align-items-center">{{ $posts->links() }}</div>
        @endif
    </div>
@endsection
