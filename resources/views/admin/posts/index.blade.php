@extends('layouts.admin')

@section('title', 'Publikasi')
@section('pretitle', 'Manajemen')
@section('page-title', 'Publikasi')

@section('page-actions')
    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
        <i class="ti ti-plus me-1"></i> Tulis Konten
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
                        <option value="menunggu" @selected(request('status') === 'menunggu')>Menunggu tinjauan</option>
                    </select>
                </div>
                <div class="col-auto">
                    <select name="jenis" class="form-select" aria-label="Jenis konten" onchange="this.form.submit()">
                        <option value="">Semua jenis</option>
                        @foreach (\App\Models\Post::JENIS as $value => $label)<option value="{{ $value }}" @selected(request('jenis') === $value)>{{ $label }}</option>@endforeach
                    </select>
                </div>
                <div class="col">
                    <input type="search" name="cari" value="{{ request('cari') }}" class="form-control" aria-label="Cari judul" placeholder="Cari judul">
                </div>
                <div class="col-auto">
                    <button class="btn">Cari</button>
                    @if (request('status') || request('jenis') || request('cari'))
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-link">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Jenis / Penulis</th>
                        <th>Kategori</th>
                        <th class="w-1">Status</th>
                        <th class="w-1">Terbit</th>
                        <th class="w-1"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($posts as $post)
                        <tr>
                            <td><div class="fw-bold">{{ $post->judul }}</div></td>
                            <td><div>{{ \App\Models\Post::JENIS[$post->jenis] ?? 'Berita' }}</div><div class="text-secondary small">{{ $post->user?->name }}</div></td>
                            <td>
                                @foreach ($post->categories as $c)
                                    <span class="badge bg-blue-lt">{{ $c->nama }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if ($post->status === 'published')
                                    <span class="badge bg-green-lt">Terbit</span>
                                @elseif ($post->isSubmitted())
                                    <span class="badge bg-yellow-lt">Menunggu tinjauan</span>
                                @else
                                    <span class="badge bg-yellow-lt">Draft</span>
                                @endif
                            </td>
                            <td class="text-secondary">{{ $post->published_at?->translatedFormat('d M Y') ?: '—' }}</td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm">Edit</a>
                                    @if ($post->isSubmitted())
                                        <form method="POST" action="{{ route('admin.posts.return-to-draft', $post) }}">@csrf<button class="btn btn-sm">Kembalikan</button></form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.posts.destroy', $post) }}"
                                          onsubmit="return confirm('Hapus berita ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-ghost-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-secondary py-4">Belum ada publikasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($posts->hasPages())
            <div class="card-footer d-flex">{{ $posts->links() }}</div>
        @endif
    </div>
@endsection
