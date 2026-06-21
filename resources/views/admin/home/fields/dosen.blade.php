{{-- Field blok dosen (dinamis). Params: $i, $d --}}
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
<div class="row">
    <div class="col-md-4 mb-2">
        <label class="form-label">Jumlah dosen</label>
        <input type="number" min="1" max="12" class="form-control" name="blocks[{{ $i }}][data][count]" value="{{ $d['count'] ?? '6' }}">
    </div>
    <div class="col-md-4 mb-2">
        <label class="form-label">Label Tautan</label>
        <input class="form-control" name="blocks[{{ $i }}][data][link_label]" value="{{ $d['link_label'] ?? '' }}">
    </div>
    <div class="col-md-4 mb-2">
        <label class="form-label">URL Tautan</label>
        <input class="form-control" name="blocks[{{ $i }}][data][link_url]" value="{{ $d['link_url'] ?? '' }}">
    </div>
</div>
