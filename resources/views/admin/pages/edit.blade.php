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

        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label required">Judul</label>
                    <input type="text" name="title" value="{{ old('title', $page->title) }}" class="form-control" required>
                </div>
                <div class="mb-1">
                    <label class="form-label">Konten</label>
                    <textarea id="editor-konten" name="content" rows="16" class="form-control">{{ old('content', $page->content) }}</textarea>
                </div>
            </div>
            <div class="card-footer d-flex">
                <a href="{{ route('admin.pages.index') }}" class="btn btn-link">Batal</a>
                <button class="btn btn-primary ms-auto">Simpan</button>
            </div>
        </div>
    </form>
@endsection

@include('admin.partials.tinymce', ['selector' => '#editor-konten'])
