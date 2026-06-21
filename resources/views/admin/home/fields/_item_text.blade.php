{{-- Baris item teks (checklist) untuk blok about. Params: $i, $j, $it --}}
<div class="row g-2 mb-2 align-items-center item-row">
    <div class="col"><input class="form-control form-control-sm" placeholder="Poin keunggulan…" name="blocks[{{ $i }}][data][items][{{ $j }}][text]" value="{{ $it['text'] ?? '' }}"></div>
    <div class="col-auto"><button type="button" class="btn btn-icon btn-sm btn-ghost-danger" data-del-item aria-label="Hapus"><i class="ti ti-x"></i></button></div>
</div>
