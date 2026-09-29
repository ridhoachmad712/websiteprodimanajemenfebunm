{{-- Baris item statistik. Params: $i (index blok), $j (index item), $it --}}
<div class="row g-2 mb-2 align-items-center item-row">
    <div class="col-5 col-sm-3"><input class="form-control form-control-sm" aria-label="Ikon" placeholder="ti-users" name="blocks[{{ $i }}][data][items][{{ $j }}][icon]" value="{{ $it['icon'] ?? '' }}"></div>
    <div class="col-7 col-sm-3"><input class="form-control form-control-sm" aria-label="Nilai statistik" placeholder="Nilai (mis. 1200 / Unggul)" name="blocks[{{ $i }}][data][items][{{ $j }}][value]" value="{{ $it['value'] ?? '' }}"></div>
    <div class="col-4 col-sm-2"><input class="form-control form-control-sm" aria-label="Akhiran nilai" placeholder="+ (akhiran)" name="blocks[{{ $i }}][data][items][{{ $j }}][suffix]" value="{{ $it['suffix'] ?? '' }}"></div>
    <div class="col-6 col-sm-3"><input class="form-control form-control-sm" aria-label="Label statistik" placeholder="Label" name="blocks[{{ $i }}][data][items][{{ $j }}][label]" value="{{ $it['label'] ?? '' }}"></div>
    <div class="col-2 col-sm-1 text-end"><button type="button" class="btn btn-icon btn-sm btn-ghost-danger" data-del-item aria-label="Hapus"><i class="ti ti-x"></i></button></div>
</div>
