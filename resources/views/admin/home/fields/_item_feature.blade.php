{{-- Baris item untuk blok features. Params: $i (index blok), $j (index item), $it --}}
<div class="row g-2 mb-2 align-items-center item-row">
    <div class="col-5 col-sm-3"><input class="form-control form-control-sm" aria-label="Ikon" placeholder="ti-tools" name="blocks[{{ $i }}][data][items][{{ $j }}][icon]" value="{{ $it['icon'] ?? '' }}"></div>
    <div class="col-7 col-sm-3"><input class="form-control form-control-sm" aria-label="Judul item" placeholder="Judul" name="blocks[{{ $i }}][data][items][{{ $j }}][title]" value="{{ $it['title'] ?? '' }}"></div>
    <div class="col-10 col-sm-5"><textarea class="form-control form-control-sm" aria-label="Deskripsi item" placeholder="Deskripsi" rows="2" name="blocks[{{ $i }}][data][items][{{ $j }}][desc]">{{ $it['desc'] ?? '' }}</textarea></div>
    <div class="col-2 col-sm-1 text-end"><button type="button" class="btn btn-icon btn-sm btn-ghost-danger" data-del-item aria-label="Hapus"><i class="ti ti-x"></i></button></div>
</div>
