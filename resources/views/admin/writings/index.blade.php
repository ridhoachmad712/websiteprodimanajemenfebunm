@extends('layouts.admin')

@section('title', 'Tulisan Saya')
@section('pretitle', 'Ruang Dosen')
@section('page-title', 'Tulisan Saya')

@section('page-actions')
    <a href="{{ route('admin.writings.create') }}" class="btn btn-primary"><i class="ti ti-plus me-1" aria-hidden="true"></i>Tulis Baru</a>
@endsection

@section('content')
    <div class="card writing-list">
        <div class="list-group list-group-flush">
            @forelse ($posts as $post)
                <div class="list-group-item py-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-12 col-lg">
                            <div class="fw-semibold mb-1">{{ $post->judul }}</div>
                            <div class="d-flex flex-wrap align-items-center gap-2 text-secondary small">
                                <span>{{ \App\Models\Post::JENIS[$post->jenis] }}</span>
                                <span aria-hidden="true">&middot;</span>
                                <span>Diperbarui {{ $post->updated_at?->translatedFormat('d M Y') }}</span>
                                @if ($post->status === 'published')<span class="badge bg-green-lt">Terbit</span>
                                @elseif ($post->isSubmitted())<span class="badge bg-yellow-lt">Menunggu tinjauan</span>
                                @else<span class="badge bg-secondary-lt">Draft</span>@endif
                            </div>
                        </div>
                        <div class="col-12 col-lg-auto">
                            <div class="d-flex flex-wrap gap-2">
                                @if ($post->status === 'published')
                                    <a href="{{ $post->url() }}" class="btn btn-sm" target="_blank" rel="noopener">Lihat tulisan</a>
                                @elseif ($post->isSubmitted())
                                    <form method="POST" action="{{ route('admin.writings.withdraw', $post) }}">@csrf<button class="btn btn-sm">Tarik pengajuan</button></form>
                                @elseif (! $post->isSubmitted())
                                    <a href="{{ route('admin.writings.edit', $post) }}" class="btn btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('admin.writings.submit', $post) }}">@csrf<button class="btn btn-sm btn-primary">Kirim untuk ditinjau</button></form>
                                    <form method="POST" action="{{ route('admin.writings.destroy', $post) }}" onsubmit="return confirm('Hapus draft ini?')">@csrf @method('DELETE')<button class="btn btn-sm btn-ghost-danger">Hapus</button></form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="list-group-item text-center text-secondary py-4">Belum ada tulisan. Mulai dengan membuat draft baru.</div>
            @endforelse
        </div>
        @if ($posts->hasPages())<div class="card-footer">{{ $posts->links() }}</div>@endif
    </div>
@endsection
