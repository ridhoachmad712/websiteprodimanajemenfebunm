{{-- Field blok statistik. Params: $i, $d --}}
<div class="row">
    <div class="col-md-5 mb-2">
        <label class="form-label">Eyebrow</label>
        <input class="form-control" name="blocks[{{ $i }}][data][eyebrow]" value="{{ $d['eyebrow'] ?? '' }}">
    </div>
    <div class="col-md-5 mb-2">
        <label class="form-label">Judul</label>
        <input class="form-control" name="blocks[{{ $i }}][data][title]" value="{{ $d['title'] ?? '' }}">
    </div>
    <div class="col-md-2 mb-2">
        <label class="form-label">Gaya</label>
        <select class="form-select" name="blocks[{{ $i }}][data][style]">
            @foreach (['dark' => 'Gelap', 'tint' => 'Tint', 'normal' => 'Normal'] as $k => $lbl)
                <option value="{{ $k }}" @selected(($d['style'] ?? 'dark') === $k)>{{ $lbl }}</option>
            @endforeach
        </select>
    </div>
</div>

<label class="form-label">Item angka</label>
<div class="form-hint mb-2">Nilai boleh angka (dianimasikan naik) atau teks (mis. "Unggul"). Akhiran opsional (mis. "+").</div>
<div data-items>
    @foreach ($d['items'] ?? [] as $j => $it)
        @include('admin.home.fields._item_statistik', ['i' => $i, 'j' => $j, 'it' => $it])
    @endforeach
</div>
<template class="tpl-item">@include('admin.home.fields._item_statistik', ['i' => $i, 'j' => '__J__', 'it' => []])</template>
<button type="button" class="btn btn-sm btn-outline-primary mt-1" data-add-item><i class="ti ti-plus me-1"></i>Tambah item</button>
