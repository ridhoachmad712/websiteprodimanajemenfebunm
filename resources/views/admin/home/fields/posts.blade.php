{{-- Field blok posts (dinamis). Params: $i, $d --}}
@php($cats = \App\Models\Category::orderBy('nama')->get())
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
    <div class="col-md-5 mb-2">
        <label class="form-label">Kategori</label>
        <select class="form-select" name="blocks[{{ $i }}][data][category]">
            <option value="">— Semua / Terbaru —</option>
            @foreach ($cats as $c)
                <option value="{{ $c->slug }}" @selected(($d['category'] ?? '') === $c->slug)>{{ $c->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 mb-2">
        <label class="form-label">Jumlah item</label>
        <input type="number" min="1" max="12" class="form-control" name="blocks[{{ $i }}][data][count]" value="{{ $d['count'] ?? '3' }}">
    </div>
    <div class="col-md-4 mb-2">
        <label class="form-label">Gaya latar</label>
        <select class="form-select" name="blocks[{{ $i }}][data][style]">
            @foreach (['normal' => 'Normal (putih abu)', 'tint' => 'Tint (putih)', 'dark' => 'Gelap'] as $k => $lbl)
                <option value="{{ $k }}" @selected(($d['style'] ?? 'normal') === $k)>{{ $lbl }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-2">
        <label class="form-label">Label Tautan "Lihat Semua"</label>
        <input class="form-control" name="blocks[{{ $i }}][data][link_label]" value="{{ $d['link_label'] ?? '' }}" placeholder="Kosongkan untuk sembunyikan">
    </div>
    <div class="col-md-6 mb-2">
        <label class="form-label">URL Tautan</label>
        <input class="form-control" name="blocks[{{ $i }}][data][link_url]" value="{{ $d['link_url'] ?? '' }}">
    </div>
</div>
