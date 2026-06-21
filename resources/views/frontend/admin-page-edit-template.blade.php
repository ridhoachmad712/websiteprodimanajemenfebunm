@extends('layouts.admin')

@php
    $template = $page->template();
    $data = $page->sections['_data'] ?? [];
@endphp

@section('title', 'Edit Halaman')
@section('pretitle', 'Manajemen')
@section('page-title', 'Edit: '.$page->title)

@section('page-actions')
    <a href="{{ $page->publicUrl() }}?preview=1" target="_blank" class="btn btn-outline-secondary"><i class="ti ti-eye me-1"></i>Preview</a>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.pages.update', $page) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <label class="form-label required">Judul Halaman</label>
                        <input type="text" name="title" value="{{ old('title', $page->title) }}" class="form-control" required>
                    </div>
                </div>

                @if ($template === 'sections')
                    @include('frontend.admin-page-template-sections', ['page' => $page])
                @else
                    @include('frontend.admin-page-template-fields', ['template' => $template, 'data' => $data])
                @endif
            </div>
            <div class="col-lg-4">
                <div class="card position-sticky" style="top:5rem">
                    <div class="card-header"><h3 class="card-title">Publikasi & SEO</h3></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Bentuk</label>
                            <div><span class="badge bg-purple-lt">{{ $templates[$template]['label'] ?? Str::headline($template) }}</span></div>
                        </div>
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select mb-3">
                            <option value="published" @selected(old('status', $page->status) === 'published')>Published</option>
                            <option value="draft" @selected(old('status', $page->status) === 'draft')>Draft</option>
                        </select>
                        <label class="form-label">Meta Title</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" class="form-control mb-3">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_description" rows="3" class="form-control mb-3">{{ old('meta_description', $page->meta_description) }}</textarea>
                        <label class="form-label">OG Image</label>
                        <input type="file" name="og_image" class="form-control" accept="image/*">
                    </div>
                    <div class="card-footer d-flex">
                        <a href="{{ route('admin.pages.index') }}" class="btn btn-link">Batal</a>
                        <button class="btn btn-primary ms-auto">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@include('admin.partials.tinymce', ['selector' => '.editor-template, .editor-section', 'height' => 260])
