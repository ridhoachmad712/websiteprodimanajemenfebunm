@extends('layouts.admin')

@section('title', 'Edit Halaman')
@section('pretitle', 'Manajemen')
@section('page-title', 'Edit: '.$page->title)

@section('content')
    <form method="POST" action="{{ route('admin.pages.update', $page) }}">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <div class="card mb-3">
            <div class="card-body">
                <label class="form-label required">Judul Halaman</label>
                <input type="text" name="title" value="{{ old('title', $page->title) }}" class="form-control" required>
            </div>
        </div>

        @foreach ($page->sections as $key => $section)
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Section: {{ $section['judul'] ?? $key }}</h3>
                    <div class="card-actions"><span class="text-secondary small">#{{ $key }}</span></div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Judul section</label>
                        <input type="text" name="sections[{{ $key }}][judul]"
                               value="{{ old('sections.'.$key.'.judul', $section['judul'] ?? '') }}" class="form-control">
                    </div>
                    <div class="mb-1">
                        <label class="form-label">Isi</label>
                        <textarea name="sections[{{ $key }}][isi]" rows="8"
                                  class="form-control editor-section">{{ old('sections.'.$key.'.isi', $section['isi'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="card">
            <div class="card-body d-flex">
                <a href="{{ route('admin.pages.index') }}" class="btn btn-link">Batal</a>
                <button class="btn btn-primary ms-auto">Simpan</button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '.editor-section', height: 240, menubar: false,
            plugins: 'lists link table code autolink',
            toolbar: 'undo redo | bold italic | bullist numlist | link | code',
            branding: false, promotion: false,
        });
    </script>
@endpush
