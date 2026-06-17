@extends('layouts.admin')

@section('title', 'Galeri')
@section('pretitle', 'Manajemen')
@section('page-title', 'Galeri')

@section('page-actions')
    <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">
        <i class="ti ti-upload me-1"></i> Unggah Gambar
    </a>
@endsection

@section('content')
    @if ($items->isEmpty())
        <div class="card"><div class="card-body">
            <div class="empty">
                <p class="empty-title">Belum ada gambar</p>
                <p class="empty-subtitle text-secondary">Unggah gambar pertama untuk mengisi galeri.</p>
            </div>
        </div></div>
    @else
        <div class="row row-cards">
            @foreach ($items as $item)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card">
                        <img src="{{ Storage::url($item->gambar) }}" class="card-img-top" alt="{{ $item->judul }}" style="aspect-ratio:4/3;object-fit:cover">
                        <div class="card-body p-2">
                            <div class="text-truncate fw-bold small">{{ $item->judul }}</div>
                            @if ($item->kategori)<div class="text-secondary small">{{ $item->kategori }}</div>@endif
                            <div class="btn-list mt-2">
                                <a href="{{ route('admin.gallery.edit', $item) }}" class="btn btn-sm w-100">Edit</a>
                                <form method="POST" action="{{ route('admin.gallery.destroy', $item) }}" onsubmit="return confirm('Hapus gambar ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-ghost-danger w-100">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($items->hasPages())
            <div class="mt-3">{{ $items->links() }}</div>
        @endif
    @endif
@endsection
