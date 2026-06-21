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

        <div id="sectionWrap">
            @foreach ($page->sections as $key => $section)
                <div class="card mb-3 section-row">
                    <div class="card-header">
                        <h3 class="card-title">Section: {{ $section['judul'] ?? $key }}</h3>
                        <div class="card-actions">
                            <button type="button" class="btn btn-icon btn-sm btn-ghost-danger" data-del-section title="Hapus section"><i class="ti ti-trash"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">ID section</label>
                                <input type="text" name="sections[{{ $loop->index }}][key]" value="{{ old('sections.'.$loop->index.'.key', $key) }}" class="form-control">
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Judul section</label>
                                <input type="text" name="sections[{{ $loop->index }}][judul]" value="{{ old('sections.'.$loop->index.'.judul', $section['judul'] ?? '') }}" class="form-control">
                            </div>
                        </div>
                        <label class="form-label">Isi</label>
                        <textarea name="sections[{{ $loop->index }}][isi]" rows="8" class="form-control editor-section">{{ old('sections.'.$loop->index.'.isi', $section['isi'] ?? '') }}</textarea>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card">
            <div class="card-body d-flex">
                <button type="button" class="btn btn-outline-primary" data-add-section><i class="ti ti-plus me-1"></i>Tambah Section</button>
                <a href="{{ route('admin.pages.index') }}" class="btn btn-link ms-auto">Batal</a>
                <button class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>Simpan</button>
            </div>
        </div>
    </form>

    <template id="sectionTpl">
        <div class="card mb-3 section-row">
            <div class="card-header">
                <h3 class="card-title">Section Baru</h3>
                <div class="card-actions"><button type="button" class="btn btn-icon btn-sm btn-ghost-danger" data-del-section><i class="ti ti-trash"></i></button></div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3"><label class="form-label">ID section</label><input type="text" name="sections[__I__][key]" class="form-control"></div>
                    <div class="col-md-8 mb-3"><label class="form-label">Judul section</label><input type="text" name="sections[__I__][judul]" class="form-control"></div>
                </div>
                <label class="form-label">Isi</label>
                <textarea name="sections[__I__][isi]" rows="8" class="form-control editor-section"></textarea>
            </div>
        </div>
    </template>
@endsection

@include('admin.partials.tinymce', ['selector' => '.editor-section', 'height' => 240])

@push('scripts')
<script>
    (function () {
        var index = {{ count($page->sections ?? []) + 1 }};
        var wrap = document.getElementById('sectionWrap');
        var tpl = document.getElementById('sectionTpl');

        document.addEventListener('click', function (e) {
            if (e.target.closest('[data-add-section]') && wrap && tpl) {
                wrap.insertAdjacentHTML('beforeend', tpl.innerHTML.replaceAll('__I__', index++));
            }
            if (e.target.closest('[data-del-section]')) e.target.closest('.section-row').remove();
        });
    })();
</script>
@endpush
