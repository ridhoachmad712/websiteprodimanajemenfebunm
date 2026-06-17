@extends('layouts.admin')

@section('title', 'Unggah Gambar')
@section('pretitle', 'Manajemen')
@section('page-title', 'Unggah Gambar')

@section('content')
    <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Judul (opsional)</label>
                        <input type="text" name="judul" value="{{ old('judul') }}" class="form-control" placeholder="Kosongkan → pakai nama berkas">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kategori / Album (opsional)</label>
                        <input type="text" name="kategori" value="{{ old('kategori') }}" class="form-control" placeholder="mis. Wisuda 2025">
                    </div>
                </div>
                <div class="mb-1">
                    <label class="form-label required">Gambar (bisa pilih banyak)</label>
                    <input type="file" name="gambar[]" accept="image/*" multiple class="form-control" required>
                    <small class="form-hint">JPG/PNG/WebP, maks 2 MB per gambar.</small>
                </div>
            </div>
            <div class="card-footer d-flex">
                <a href="{{ route('admin.gallery.index') }}" class="btn btn-link">Batal</a>
                <button class="btn btn-primary ms-auto">Unggah</button>
            </div>
        </div>
    </form>
@endsection
