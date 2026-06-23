{{-- Form bersama create & edit prestasi. Variabel: $prestasi (opsional) --}}
@php($p = $prestasi ?? null)

@if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label required">Judul Prestasi</label>
                    <input type="text" name="judul" value="{{ old('judul', $p->judul ?? '') }}"
                           class="form-control @error('judul') is-invalid @enderror" placeholder="mis. Juara 1 Business Plan Competition Nasional" required>
                    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Peraih</label>
                        <input type="text" name="peraih" value="{{ old('peraih', $p->peraih ?? '') }}" class="form-control" placeholder="Nama mahasiswa/dosen">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Penyelenggara</label>
                        <input type="text" name="penyelenggara" value="{{ old('penyelenggara', $p->penyelenggara ?? '') }}" class="form-control" placeholder="mis. Kemendikbudristek">
                    </div>
                </div>

                <div class="mb-1">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="5" class="form-control">{{ old('deskripsi', $p->deskripsi ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header"><h3 class="card-title">Detail</h3></div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label required">Kategori</label>
                    <select name="kategori" class="form-select">
                        @foreach (\App\Models\Prestasi::kategoriOptions() as $val => $lbl)
                            <option value="{{ $val }}" @selected(old('kategori', $p->kategori ?? 'mahasiswa') === $val)>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tingkat</label>
                    <select name="tingkat" class="form-select">
                        <option value="">— Tidak ditentukan —</option>
                        @foreach (\App\Models\Prestasi::tingkatOptions() as $val => $lbl)
                            <option value="{{ $val }}" @selected(old('tingkat', $p->tingkat ?? '') === $val)>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label required">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', optional($p->tanggal ?? null)->format('Y-m-d')) }}"
                           class="form-control @error('tanggal') is-invalid @enderror" required>
                    @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="published" @selected(old('status', $p->status ?? 'published') === 'published')>Published</option>
                        <option value="draft" @selected(old('status', $p->status ?? '') === 'draft')>Draft</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Foto / Sertifikat</label>
                    <input type="file" name="gambar" class="form-control" accept="image/*">
                    @if ($p?->gambar)
                        <img src="{{ Storage::url($p->gambar) }}" alt="" class="rounded mt-2" style="max-width:100%;height:auto">
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body d-flex">
        <a href="{{ route('admin.prestasi.index') }}" class="btn btn-link">Batal</a>
        <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
    </div>
</div>
