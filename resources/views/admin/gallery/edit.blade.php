@extends('layouts.admin')

@section('title', 'Edit Gambar')
@section('pretitle', 'Manajemen')
@section('page-title', 'Edit Gambar')

@section('content')
    <form method="POST" action="{{ route('admin.gallery.update', $gallery) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <div class="row">
            <div class="col-lg-4 mb-3">
                <img src="{{ Storage::url($gallery->gambar) }}" class="rounded img-fluid" alt="{{ $gallery->judul }}">
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label required">Judul</label>
                            <input type="text" name="judul" value="{{ old('judul', $gallery->judul) }}" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Kategori / Album</label>
                                <input type="text" name="kategori" value="{{ old('kategori', $gallery->kategori) }}" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Urutan</label>
                                <input type="number" name="urutan" value="{{ old('urutan', $gallery->urutan) }}" min="0" class="form-control">
                            </div>
                        </div>
                        <div class="mb-1">
                            <label class="form-label">Ganti gambar (opsional)</label>
                            <input type="file" name="gambar" accept="image/*" class="form-control">
                        </div>
                    </div>
                    <div class="card-footer d-flex">
                        <a href="{{ route('admin.gallery.index') }}" class="btn btn-link">Batal</a>
                        <button class="btn btn-primary ms-auto">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
