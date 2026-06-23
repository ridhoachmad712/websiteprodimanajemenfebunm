{{-- Form bersama create & edit kegiatan. Variabel: $kegiatan (opsional) --}}
@php($k = $kegiatan ?? null)

@if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label required">Nama Kegiatan</label>
                    <input type="text" name="judul" value="{{ old('judul', $k->judul ?? '') }}"
                           class="form-control @error('judul') is-invalid @enderror" required>
                    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <label class="form-check form-switch mb-3">
                    <input type="hidden" name="seharian" value="0">
                    <input class="form-check-input" type="checkbox" name="seharian" value="1"
                           @checked(old('seharian', $k->seharian ?? true))>
                    <span class="form-check-label">Sepanjang hari (tanpa jam)</span>
                </label>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">Mulai</label>
                        <input type="datetime-local" name="mulai"
                               value="{{ old('mulai', optional($k->mulai ?? null)->format('Y-m-d\TH:i')) }}"
                               class="form-control @error('mulai') is-invalid @enderror" required>
                        @error('mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Selesai</label>
                        <input type="datetime-local" name="selesai"
                               value="{{ old('selesai', optional($k->selesai ?? null)->format('Y-m-d\TH:i')) }}"
                               class="form-control @error('selesai') is-invalid @enderror">
                        <small class="form-hint">Kosongkan bila kegiatan satu hari/sesaat.</small>
                        @error('selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Lokasi</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi', $k->lokasi ?? '') }}" class="form-control" placeholder="mis. Aula FEB UNM">
                </div>

                <div class="mb-1">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="form-control">{{ old('deskripsi', $k->deskripsi ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header"><h3 class="card-title">Warna di Kalender</h3></div>
            <div class="card-body">
                @php($warnaNow = old('warna', $k->warna ?? ''))
                <select name="warna" class="form-select">
                    <option value="" @selected($warnaNow === '' || $warnaNow === null)>Otomatis (warna tema)</option>
                    @foreach (['#1b3a5b' => 'Navy', '#066fd1' => 'Biru', '#2f9e44' => 'Hijau', '#d63939' => 'Merah', '#f76707' => 'Oranye', '#6d28d9' => 'Ungu', '#0f766e' => 'Teal'] as $hex => $lbl)
                        <option value="{{ $hex }}" @selected($warnaNow === $hex)>{{ $lbl }}</option>
                    @endforeach
                </select>
                <small class="form-hint">Opsional. "Otomatis" memakai warna tema situs.</small>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body d-flex">
        <a href="{{ route('admin.kegiatan.index') }}" class="btn btn-link">Batal</a>
        <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
    </div>
</div>
