{{-- Form bersama untuk create & edit dosen. Variabel: $dosen (opsional), $kategori --}}
@php($d = $dosen ?? null)

@if ($errors->any())
    <div class="alert alert-danger">
        <div class="fw-bold">Periksa kembali isian berikut:</div>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="mb-3">
            <label class="form-label required">Nama lengkap &amp; gelar</label>
            <input type="text" name="nama" value="{{ old('nama', $d->nama ?? '') }}"
                   class="form-control @error('nama') is-invalid @enderror"
                   placeholder="mis. Dr. Anwar, S.E., M.Si." required>
            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label required">Kategori</label>
                <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                    <option value="">— pilih —</option>
                    @foreach ($kategori as $key => $label)
                        <option value="{{ $key }}" @selected(old('kategori', $d->kategori ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Konsentrasi</label>
                <input type="text" name="konsentrasi" value="{{ old('konsentrasi', $d->konsentrasi ?? '') }}"
                       class="form-control @error('konsentrasi') is-invalid @enderror"
                       placeholder="mis. Manajemen Keuangan">
                @error('konsentrasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">NIP</label>
                <input type="text" name="nip" value="{{ old('nip', $d->nip ?? '') }}"
                       class="form-control @error('nip') is-invalid @enderror">
                @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Urutan tampil</label>
                <input type="number" name="urutan" value="{{ old('urutan', $d->urutan ?? 0) }}" min="0"
                       class="form-control @error('urutan') is-invalid @enderror">
                @error('urutan') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Tautan Bio</label>
            <input type="url" name="bio_link" value="{{ old('bio_link', $d->bio_link ?? '') }}"
                   class="form-control @error('bio_link') is-invalid @enderror"
                   placeholder="https://…">
            @error('bio_link') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-lg-4">
        <div class="mb-3">
            <label class="form-label">Foto</label>
            @if ($d && $d->foto)
                <div class="mb-2">
                    <img src="{{ Storage::url($d->foto) }}" class="rounded" style="max-width:120px" alt="Foto {{ $d->nama }}">
                </div>
            @endif
            <input type="file" name="foto" accept="image/*"
                   class="form-control @error('foto') is-invalid @enderror">
            <small class="form-hint">JPG/PNG/WebP, maks 2 MB. {{ $d && $d->foto ? 'Kosongkan untuk mempertahankan foto saat ini.' : '' }}</small>
            @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<div class="d-flex">
    <a href="{{ route('admin.dosen.index') }}" class="btn btn-link">Batal</a>
    <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
</div>
