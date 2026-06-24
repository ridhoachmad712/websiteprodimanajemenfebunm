{{-- Form bersama create & edit mitra. Variabel: $mitra (opsional) --}}
@php($m = $mitra ?? null)

@if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label required">Nama Mitra</label>
                    <input type="text" name="nama" value="{{ old('nama', $m->nama ?? '') }}"
                           class="form-control @error('nama') is-invalid @enderror" placeholder="mis. Bank Indonesia" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Logo</label>
                    <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                    <small class="form-hint">PNG/SVG transparan disarankan (maks 2 MB). Tampil dengan tinggi seragam di beranda.</small>
                    @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @if ($m?->logo)
                        <div class="mt-2 p-2 bg-light rounded d-inline-block"><img src="{{ Storage::url($m->logo) }}" alt="" style="height:48px;width:auto;object-fit:contain"></div>
                    @endif
                </div>

                <div class="mb-1">
                    <label class="form-label">Tautan (opsional)</label>
                    <input type="url" name="url" value="{{ old('url', $m->url ?? '') }}"
                           class="form-control @error('url') is-invalid @enderror" placeholder="https://…">
                    <small class="form-hint">Bila diisi, logo dapat diklik menuju situs mitra.</small>
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
                    <label class="form-label">Urutan</label>
                    <input type="number" name="urutan" value="{{ old('urutan', $m->urutan ?? 0) }}" min="0" class="form-control">
                    <small class="form-hint">Makin kecil makin awal.</small>
                </div>
                <label class="form-check form-switch">
                    <input type="hidden" name="aktif" value="0">
                    <input class="form-check-input" type="checkbox" name="aktif" value="1" @checked(old('aktif', $m->aktif ?? true))>
                    <span class="form-check-label">Aktif (tampil di beranda)</span>
                </label>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body d-flex">
        <a href="{{ route('admin.mitra.index') }}" class="btn btn-link">Batal</a>
        <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
    </div>
</div>
