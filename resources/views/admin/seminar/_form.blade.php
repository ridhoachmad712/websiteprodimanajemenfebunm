{{-- Form bersama create & edit seminar. Variabel: $seminar (opsional) --}}
@php($s = $seminar ?? null)

@if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label required">Nama Mahasiswa</label>
                        <input type="text" name="nama" value="{{ old('nama', $s->nama ?? '') }}"
                               class="form-control @error('nama') is-invalid @enderror" required>
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">NIM</label>
                        <input type="text" name="nim" value="{{ old('nim', $s->nim ?? '') }}" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label required">Judul Tugas Akhir</label>
                    <textarea name="judul" rows="2" class="form-control @error('judul') is-invalid @enderror" required>{{ old('judul', $s->judul ?? '') }}</textarea>
                    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pembimbing</label>
                        <input type="text" name="pembimbing" value="{{ old('pembimbing', $s->pembimbing ?? '') }}" class="form-control" placeholder="Pembimbing 1 & 2">
                    </div>
                    <div class="col-md-6 mb-1">
                        <label class="form-label">Penguji</label>
                        <input type="text" name="penguji" value="{{ old('penguji', $s->penguji ?? '') }}" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header"><h3 class="card-title">Jadwal</h3></div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label required">Jenis</label>
                    <select name="jenis" class="form-select">
                        @foreach (\App\Models\Seminar::jenisOptions() as $val => $lbl)
                            <option value="{{ $val }}" @selected(old('jenis', $s->jenis ?? 'proposal') === $val)>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label required">Tanggal & Waktu</label>
                    <input type="datetime-local" name="tanggal"
                           value="{{ old('tanggal', optional($s->tanggal ?? null)->format('Y-m-d\TH:i')) }}"
                           class="form-control @error('tanggal') is-invalid @enderror" required>
                    @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Tempat</label>
                    <input type="text" name="tempat" value="{{ old('tempat', $s->tempat ?? '') }}" class="form-control" placeholder="mis. Ruang Sidang 1">
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="published" @selected(old('status', $s->status ?? 'published') === 'published')>Published</option>
                        <option value="draft" @selected(old('status', $s->status ?? '') === 'draft')>Draft</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body d-flex">
        <a href="{{ route('admin.seminar.index') }}" class="btn btn-link">Batal</a>
        <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
    </div>
</div>
