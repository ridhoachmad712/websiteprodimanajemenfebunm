{{-- Field blok video. Params: $i, $d --}}
<div class="row">
    <div class="col-md-5 mb-2">
        <label class="form-label">Eyebrow</label>
        <input class="form-control" name="blocks[{{ $i }}][data][eyebrow]" value="{{ $d['eyebrow'] ?? '' }}" placeholder="Contoh: Video Profil">
    </div>
    <div class="col-md-7 mb-2">
        <label class="form-label">Judul</label>
        <input class="form-control" name="blocks[{{ $i }}][data][title]" value="{{ $d['title'] ?? '' }}" placeholder="Contoh: Mengenal Prodi Manajemen FEB UNM">
    </div>
</div>

<div class="mb-2">
    <label class="form-label">Subjudul</label>
    <textarea class="form-control" name="blocks[{{ $i }}][data][subtitle]" rows="2" placeholder="Teks singkat yang tampil di atas video">{{ $d['subtitle'] ?? '' }}</textarea>
</div>

<div class="row">
    <div class="col-md-8 mb-2">
        <label class="form-label">URL / ID Video YouTube</label>
        <input class="form-control" name="blocks[{{ $i }}][data][video_url]" value="{{ $d['video_url'] ?? '' }}" placeholder="https://www.youtube.com/watch?v=xxxxxxxxxxx">
        <div class="form-hint">Bisa memakai URL YouTube, youtu.be, Shorts, Live, Embed, atau ID video 11 karakter.</div>
    </div>
    <div class="col-md-4 mb-2">
        <label class="form-label">Gaya latar</label>
        <select class="form-select" name="blocks[{{ $i }}][data][style]">
            @foreach (['normal' => 'Normal (abu)', 'tint' => 'Putih', 'dark' => 'Gelap'] as $k => $lbl)
                <option value="{{ $k }}" @selected(($d['style'] ?? 'tint') === $k)>{{ $lbl }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="mb-2">
    <label class="form-label">Caption Video</label>
    <input class="form-control" name="blocks[{{ $i }}][data][caption]" value="{{ $d['caption'] ?? '' }}" placeholder="Opsional, tampil kecil di bawah video">
</div>
