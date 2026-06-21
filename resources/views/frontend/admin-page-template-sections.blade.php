<div id="sectionWrap">
    @foreach (($page->sections ?? []) as $key => $section)
        @continue(str_starts_with((string) $key, '_'))
        <div class="card mb-3 section-row">
            <div class="card-header">
                <h3 class="card-title">Section: {{ $section['judul'] ?? $key }}</h3>
                <div class="card-actions"><button type="button" class="btn btn-icon btn-sm btn-ghost-danger" data-del-row><i class="ti ti-trash"></i></button></div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3"><label class="form-label">ID section</label><input type="text" name="sections[{{ $loop->index }}][key]" value="{{ $key }}" class="form-control"></div>
                    <div class="col-md-8 mb-3"><label class="form-label">Judul section</label><input type="text" name="sections[{{ $loop->index }}][judul]" value="{{ $section['judul'] ?? '' }}" class="form-control"></div>
                </div>
                <label class="form-label">Isi</label>
                <textarea name="sections[{{ $loop->index }}][isi]" rows="8" class="form-control editor-section">{{ $section['isi'] ?? '' }}</textarea>
            </div>
        </div>
    @endforeach
</div>
<button type="button" class="btn btn-outline-primary mb-3" data-add-row="section"><i class="ti ti-plus me-1"></i>Tambah Section</button>

<template id="tpl-section">
    <div class="card mb-3 section-row">
        <div class="card-header"><h3 class="card-title">Section Baru</h3><div class="card-actions"><button type="button" class="btn btn-icon btn-sm btn-ghost-danger" data-del-row><i class="ti ti-trash"></i></button></div></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3"><label class="form-label">ID section</label><input type="text" name="sections[__I__][key]" class="form-control"></div>
                <div class="col-md-8 mb-3"><label class="form-label">Judul section</label><input type="text" name="sections[__I__][judul]" class="form-control"></div>
            </div>
            <label class="form-label">Isi</label><textarea name="sections[__I__][isi]" rows="8" class="form-control editor-section"></textarea>
        </div>
    </div>
</template>

@push('scripts')
<script>
    (function () {
        var idx = {{ count($page->sections ?? []) + 1 }};
        document.addEventListener('click', function (e) {
            if (e.target.closest('[data-add-row="section"]')) document.getElementById('sectionWrap').insertAdjacentHTML('beforeend', document.getElementById('tpl-section').innerHTML.replaceAll('__I__', idx++));
            if (e.target.closest('[data-del-row]')) e.target.closest('.section-row').remove();
        });
    })();
</script>
@endpush
