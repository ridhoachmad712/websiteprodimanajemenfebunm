{{-- Field blok konten bebas (WYSIWYG). Params: $i, $d --}}
<label class="form-label">Konten</label>
<textarea class="form-control block-richtext" rows="6" name="blocks[{{ $i }}][data][html]">{{ $d['html'] ?? '' }}</textarea>
<div class="form-hint mt-1">Bisa menyisipkan gambar, tabel, tautan. Disusun bebas sesuai kebutuhan.</div>
