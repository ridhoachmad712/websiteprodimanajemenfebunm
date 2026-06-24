{{-- Field blok mitra. Params: $i, $d --}}
<div class="alert alert-info">
    Logo diambil dari menu <a href="{{ route('admin.mitra.index') }}" target="_blank">Mitra &amp; Kerjasama</a> (yang berstatus aktif). Blok ini hanya mengatur judul & tampilannya.
</div>
<div class="row">
    <div class="col-md-5 mb-2">
        <label class="form-label">Eyebrow</label>
        <input class="form-control" name="blocks[{{ $i }}][data][eyebrow]" value="{{ $d['eyebrow'] ?? '' }}">
    </div>
    <div class="col-md-7 mb-2">
        <label class="form-label">Judul</label>
        <input class="form-control" name="blocks[{{ $i }}][data][title]" value="{{ $d['title'] ?? 'Mitra & Kerjasama' }}">
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-2">
        <label class="form-label">Gaya latar</label>
        <select class="form-select" name="blocks[{{ $i }}][data][style]">
            @foreach (['tint' => 'Tint (putih)', 'normal' => 'Normal (abu)'] as $k => $lbl)
                <option value="{{ $k }}" @selected(($d['style'] ?? 'tint') === $k)>{{ $lbl }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 mb-2 d-flex align-items-end">
        <label class="form-check form-switch">
            <input type="hidden" name="blocks[{{ $i }}][data][grayscale]" value="0">
            <input class="form-check-input" type="checkbox" name="blocks[{{ $i }}][data][grayscale]" value="1" @checked(($d['grayscale'] ?? '0') === '1')>
            <span class="form-check-label">Logo abu-abu (berwarna saat disentuh)</span>
        </label>
    </div>
</div>
