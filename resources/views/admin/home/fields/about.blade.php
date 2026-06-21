{{-- Field blok about. Params: $i, $d --}}
<div class="row">
    <div class="col-md-5 mb-2">
        <label class="form-label">Eyebrow</label>
        <input class="form-control" name="blocks[{{ $i }}][data][eyebrow]" value="{{ $d['eyebrow'] ?? '' }}">
    </div>
    <div class="col-md-7 mb-2">
        <label class="form-label">Judul</label>
        <input class="form-control" name="blocks[{{ $i }}][data][title]" value="{{ $d['title'] ?? '' }}">
    </div>
</div>
<div class="mb-2">
    <label class="form-label">Isi / Paragraf</label>
    <textarea class="form-control" rows="3" name="blocks[{{ $i }}][data][body]">{{ $d['body'] ?? '' }}</textarea>
</div>
<div class="mb-2">
    <label class="form-label">URL Gambar (opsional)</label>
    <input class="form-control" name="blocks[{{ $i }}][data][image_url]" value="{{ $d['image_url'] ?? '' }}" placeholder="https://… atau /storage/…">
    <div class="form-hint">Kosongkan untuk tanpa gambar. Upload gambar bisa lewat blok "Konten Bebas".</div>
</div>
<div class="row">
    <div class="col-md-6 mb-2">
        <label class="form-label">Label Tombol</label>
        <input class="form-control" name="blocks[{{ $i }}][data][button_label]" value="{{ $d['button_label'] ?? '' }}">
    </div>
    <div class="col-md-6 mb-2">
        <label class="form-label">Link Tombol</label>
        <input class="form-control" name="blocks[{{ $i }}][data][button_url]" value="{{ $d['button_url'] ?? '' }}">
    </div>
</div>

<label class="form-label">Poin Keunggulan</label>
<div data-items>
    @foreach ($d['items'] ?? [] as $j => $it)
        @include('admin.home.fields._item_text', ['i' => $i, 'j' => $j, 'it' => $it])
    @endforeach
</div>
<template class="tpl-item">@include('admin.home.fields._item_text', ['i' => $i, 'j' => '__J__', 'it' => []])</template>
<button type="button" class="btn btn-sm btn-outline-primary mt-1" data-add-item><i class="ti ti-plus me-1"></i>Tambah poin</button>
