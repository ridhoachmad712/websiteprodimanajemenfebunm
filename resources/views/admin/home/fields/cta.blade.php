{{-- Field blok CTA. Params: $i, $d --}}
<div class="mb-2">
    <label class="form-label">Judul</label>
    <input class="form-control" name="blocks[{{ $i }}][data][title]" value="{{ $d['title'] ?? '' }}">
</div>
<div class="mb-2">
    <label class="form-label">Subjudul</label>
    <input class="form-control" name="blocks[{{ $i }}][data][subtitle]" value="{{ $d['subtitle'] ?? '' }}">
</div>
<div class="row">
    <div class="col-md-3 mb-2">
        <label class="form-label">Tombol 1 — Label</label>
        <input class="form-control" name="blocks[{{ $i }}][data][btn1_label]" value="{{ $d['btn1_label'] ?? '' }}">
    </div>
    <div class="col-md-3 mb-2">
        <label class="form-label">Tombol 1 — Link</label>
        <input class="form-control" name="blocks[{{ $i }}][data][btn1_url]" value="{{ $d['btn1_url'] ?? '' }}">
    </div>
    <div class="col-md-3 mb-2">
        <label class="form-label">Tombol 2 — Label</label>
        <input class="form-control" name="blocks[{{ $i }}][data][btn2_label]" value="{{ $d['btn2_label'] ?? '' }}">
    </div>
    <div class="col-md-3 mb-2">
        <label class="form-label">Tombol 2 — Link</label>
        <input class="form-control" name="blocks[{{ $i }}][data][btn2_url]" value="{{ $d['btn2_url'] ?? '' }}">
    </div>
</div>
