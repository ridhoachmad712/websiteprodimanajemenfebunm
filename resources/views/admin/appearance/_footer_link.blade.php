{{-- Baris tautan footer. Params: $i (kolom), $j (tautan), $l --}}
<div class="row g-2 mb-2 align-items-center fcol-link">
    <div class="col-5"><input class="form-control form-control-sm" placeholder="Label" name="footer_columns[{{ $i }}][links][{{ $j }}][label]" value="{{ $l['label'] ?? '' }}"></div>
    <div class="col-6"><input class="form-control form-control-sm" placeholder="URL (https://… atau /profil)" name="footer_columns[{{ $i }}][links][{{ $j }}][url]" value="{{ $l['url'] ?? '' }}"></div>
    <div class="col-1 text-end"><button type="button" class="btn btn-icon btn-sm btn-ghost-danger" data-del-flink aria-label="Hapus"><i class="ti ti-x"></i></button></div>
</div>
