{{-- Form bersama create & edit dokumen. Variabel: $download (opsional) --}}
@php($d = $download ?? null)

@if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label required">Judul Dokumen</label>
                    <input type="text" name="judul" value="{{ old('judul', $d->judul ?? '') }}"
                           class="form-control @error('judul') is-invalid @enderror" placeholder="mis. RPS Manajemen Keuangan" required>
                    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="form-control">{{ old('deskripsi', $d->deskripsi ?? '') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Unggah File</label>
                    <input type="file" name="file" class="form-control @error('file') is-invalid @enderror">
                    <small class="form-hint">PDF, Word, Excel, PowerPoint, ZIP, atau gambar (maks 20 MB).</small>
                    @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @if ($d?->file)
                        <div class="mt-2"><span class="badge bg-blue-lt">{{ $d->ekstensi() }}</span> <a href="{{ Storage::url($d->file) }}" target="_blank" rel="noopener">File saat ini</a></div>
                    @endif
                </div>

                <div class="mb-1">
                    <label class="form-label">atau Tautan Eksternal</label>
                    <input type="url" name="url" value="{{ old('url', $d->url ?? '') }}"
                           class="form-control @error('url') is-invalid @enderror" placeholder="https://…">
                    <small class="form-hint">Isi salah satu: unggah file <em>atau</em> tautan. Bila keduanya diisi, file diutamakan.</small>
                    @error('url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header"><h3 class="card-title">Pengaturan</h3></div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label required">Kategori</label>
                    <input type="text" name="kategori" list="kategoriList" value="{{ old('kategori', $d->kategori ?? '') }}"
                           class="form-control @error('kategori') is-invalid @enderror" placeholder="mis. RPS" required>
                    <datalist id="kategoriList">
                        @foreach (\App\Models\Download::KATEGORI_UMUM as $k)<option value="{{ $k }}">@endforeach
                    </datalist>
                    <small class="form-hint">Dokumen dikelompokkan per kategori di halaman publik.</small>
                    @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Urutan</label>
                    <input type="number" name="urutan" value="{{ old('urutan', $d->urutan ?? 0) }}" min="0" class="form-control">
                    <small class="form-hint">Makin kecil makin atas dalam kategorinya.</small>
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="published" @selected(old('status', $d->status ?? 'published') === 'published')>Published</option>
                        <option value="draft" @selected(old('status', $d->status ?? '') === 'draft')>Draft</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body d-flex">
        <a href="{{ route('admin.downloads.index') }}" class="btn btn-link">Batal</a>
        <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
    </div>
</div>
