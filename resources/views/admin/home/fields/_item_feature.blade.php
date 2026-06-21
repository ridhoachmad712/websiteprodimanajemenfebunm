{{-- Baris item untuk blok features. Params: $i (index blok), $j (index item), $it --}}
<div class="row g-2 mb-2 align-items-center item-row">
    <div class="col-3"><input class="form-control form-control-sm" placeholder="ti-tools" name="blocks[{{ $i }}][data][items][{{ $j }}][icon]" value="{{ $it['icon'] ?? '' }}"></div>
    <div class="col-3"><input class="form-control form-control-sm" placeholder="Judul" name="blocks[{{ $i }}][data][items][{{ $j }}][title]" value="{{ $it['title'] ?? '' }}"></div>
    <div class="col-5"><input class="form-control form-control-sm" placeholder="Deskripsi" name="blocks[{{ $i }}][data][items][{{ $j }}][desc]" value="{{ $it['desc'] ?? '' }}"></div>
    <div class="col-1 text-end"><button type="button" class="btn btn-icon btn-sm btn-ghost-danger" data-del-item aria-label="Hapus"><i class="ti ti-x"></i></button></div>
</div>
