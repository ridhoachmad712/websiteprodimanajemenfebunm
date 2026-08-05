{{-- Form bersama create & edit hero slide. Variabel: $slide (opsional) --}}
@php($s = $slide ?? null)

@if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label @if (! $s) required @endif">Gambar</label>
                    <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror" accept="image/*" @if (! $s) required @endif>
                    <small class="form-hint">Disarankan lebar ± 1600px, rasio landscape (mis. 16:6). Maks 4 MB.</small>
                    @error('gambar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @if ($s?->gambar)
                        <img src="{{ Storage::url($s->gambar) }}" alt="" class="rounded mt-2" style="max-width:100%;height:auto">
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Judul (teks di atas gambar)</label>
                    <input type="text" name="judul" value="{{ old('judul', $s->judul ?? '') }}" class="form-control" placeholder="mis. Selamat Datang di Prodi Manajemen">
                </div>

                <div class="mb-3">
                    <label class="form-label">Subjudul</label>
                    <textarea name="subjudul" rows="2" class="form-control">{{ old('subjudul', $s->subjudul ?? '') }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Label Tombol (opsional)</label>
                        <input type="text" name="btn_label" value="{{ old('btn_label', $s->btn_label ?? '') }}" class="form-control" placeholder="mis. Selengkapnya">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">URL Tombol</label>
                        <input type="text" name="btn_url" value="{{ old('btn_url', $s->btn_url ?? '') }}" class="form-control" placeholder="/profil atau https://…">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header"><h3 class="card-title">Pengaturan</h3></div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Urutan</label>
                    <input type="number" name="urutan" value="{{ old('urutan', $s->urutan ?? 0) }}" min="0" class="form-control">
                </div>
                <label class="form-check form-switch">
                    <input type="hidden" name="aktif" value="0">
                    <input class="form-check-input" type="checkbox" name="aktif" value="1" @checked(old('aktif', $s->aktif ?? true))>
                    <span class="form-check-label">Aktif (tampil di beranda)</span>
                </label>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body d-flex">
        <a href="{{ route('admin.hero-slides.index') }}" class="btn btn-link">Batal</a>
        <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
    </div>
</div>
