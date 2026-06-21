{{-- Field blok features. Params: $i, $d --}}
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
    <label class="form-label">Subjudul</label>
    <input class="form-control" name="blocks[{{ $i }}][data][subtitle]" value="{{ $d['subtitle'] ?? '' }}">
</div>
<label class="form-check form-switch mb-3">
    <input type="hidden" name="blocks[{{ $i }}][data][center]" value="0">
    <input class="form-check-input" type="checkbox" name="blocks[{{ $i }}][data][center]" value="1" @checked((($d['center'] ?? false) == true) || (($d['center'] ?? '') === '1'))>
    <span class="form-check-label">Rata tengah</span>
</label>

<label class="form-label">Item</label>
<div data-items>
    @foreach ($d['items'] ?? [] as $j => $it)
        @include('admin.home.fields._item_feature', ['i' => $i, 'j' => $j, 'it' => $it])
    @endforeach
</div>
<template class="tpl-item">@include('admin.home.fields._item_feature', ['i' => $i, 'j' => '__J__', 'it' => []])</template>
<button type="button" class="btn btn-sm btn-outline-primary mt-1" data-add-item><i class="ti ti-plus me-1"></i>Tambah item</button>
