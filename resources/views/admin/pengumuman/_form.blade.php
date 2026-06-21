{{-- Form bersama create & edit pengumuman. Variabel: $post (opsional) --}}
@php($p = $post ?? null)

@if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label required">Judul</label>
                    <input type="text" name="judul" value="{{ old('judul', $p->judul ?? '') }}"
                           class="form-control @error('judul') is-invalid @enderror" required>
                    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-1">
                    <label class="form-label required">Isi pengumuman</label>
                    <textarea id="editor-pengumuman" name="konten" rows="10"
                              class="form-control @error('konten') is-invalid @enderror">{{ old('konten', $p->konten ?? '') }}</textarea>
                    @error('konten') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header"><h3 class="card-title">Publikasi</h3></div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft" @selected(old('status', $p->status ?? 'draft') === 'draft')>Draft</option>
                        <option value="published" @selected(old('status', $p->status ?? '') === 'published')>Terbit</option>
                    </select>
                </div>
                <div class="mb-0">
                    <label class="form-label">Tanggal terbit</label>
                    <input type="datetime-local" name="published_at"
                           value="{{ old('published_at', optional($p->published_at ?? null)->format('Y-m-d\TH:i')) }}"
                           class="form-control @error('published_at') is-invalid @enderror">
                    <small class="form-hint">Kosongkan → otomatis "sekarang" saat Terbit.</small>
                    @error('published_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body d-flex">
        <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-link">Batal</a>
        <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
    </div>
</div>

@include('admin.partials.tinymce', ['selector' => '#editor-pengumuman', 'height' => 300])
