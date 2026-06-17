@extends('layouts.frontend')

@section('title', 'Galeri')
@section('meta_description', 'Galeri kegiatan dan dokumentasi Program Studi Manajemen FEB UNM.')

@section('content')
    <section class="py-5 bg-light border-bottom">
        <div class="container-xl">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrows">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Galeri</li>
                </ol>
            </nav>
            <h1 class="mt-2 mb-0">Galeri</h1>
        </div>
    </section>

    <section class="py-5">
        <div class="container-xl">
            @forelse ($grup as $kategori => $items)
                <div class="mb-5">
                    <h2 class="h3 mb-3">{{ $kategori }}</h2>
                    <div class="row g-3">
                        @foreach ($items as $item)
                            <div class="col-6 col-md-4 col-lg-3">
                                <a href="#" class="d-block" data-bs-toggle="modal" data-bs-target="#galleryModal"
                                   data-img="{{ Storage::url($item->gambar) }}" data-judul="{{ $item->judul }}">
                                    <img src="{{ Storage::url($item->gambar) }}" class="rounded w-100" alt="{{ $item->judul }}" loading="lazy"
                                         style="aspect-ratio:1/1;object-fit:cover;cursor:zoom-in">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="empty">
                    <p class="empty-title">Galeri masih kosong</p>
                    <p class="empty-subtitle text-secondary">Dokumentasi kegiatan akan tampil di sini.</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- Lightbox sederhana --}}
    <div class="modal modal-blur fade" id="galleryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="galleryModalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img src="" id="galleryModalImg" class="img-fluid" alt="">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('galleryModal')?.addEventListener('show.bs.modal', function (e) {
        const t = e.relatedTarget;
        this.querySelector('#galleryModalImg').src = t.getAttribute('data-img');
        this.querySelector('#galleryModalTitle').textContent = t.getAttribute('data-judul') || '';
    });
</script>
@endpush
