{{-- Form bersama create & edit berita eksternal. Variabel: $berita (opsional) --}}
@php($b = $berita ?? null)

@if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label required">Judul Berita</label>
                    <input type="text" name="judul" value="{{ old('judul', $b->judul ?? '') }}"
                           class="form-control @error('judul') is-invalid @enderror" placeholder="Judul sesuai artikel di media" required>
                    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label required">Tautan Artikel (URL)</label>
                    <input type="url" name="url" value="{{ old('url', $b->url ?? '') }}"
                           class="form-control @error('url') is-invalid @enderror" placeholder="https://…" required>
                    <small class="form-hint">Alamat lengkap artikel di situs media eksternal.</small>
                    @error('url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-1">
                    <label class="form-label">Ringkasan (opsional)</label>
                    <textarea name="ringkasan" rows="3" class="form-control">{{ old('ringkasan', $b->ringkasan ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header"><h3 class="card-title">Detail</h3></div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label required">Nama Media</label>
                    <input type="text" name="sumber" value="{{ old('sumber', $b->sumber ?? '') }}"
                           class="form-control @error('sumber') is-invalid @enderror" placeholder="mis. Tribun Timur" required>
                    @error('sumber') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Terbit</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', optional($b->tanggal ?? null)->format('Y-m-d')) }}" class="form-control">
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="published" @selected(old('status', $b->status ?? 'published') === 'published')>Published</option>
                        <option value="draft" @selected(old('status', $b->status ?? '') === 'draft')>Draft</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body d-flex">
        <a href="{{ route('admin.berita-eksternal.index') }}" class="btn btn-link">Batal</a>
        <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
    </div>
</div>
