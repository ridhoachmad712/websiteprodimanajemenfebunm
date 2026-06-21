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
                <select name="kategori" id="kategoriSelect" class="form-select @error('kategori') is-invalid @enderror" required>
                    <option value="">— pilih —</option>
                    @foreach ($kategori as $key => $label)
                        <option value="{{ $key }}" @selected(old('kategori', $d->kategori ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Konsentrasi</label>
                <select name="konsentrasi" id="konsentrasiSelect" data-current="{{ old('konsentrasi', $d->konsentrasi ?? '') }}"
                        class="form-select @error('konsentrasi') is-invalid @enderror">
                    <option value="">— pilih kategori dulu —</option>
                </select>
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
            <label class="form-label">Kepakaran</label>
            <input type="text" name="kepakaran" value="{{ old('kepakaran', $d->kepakaran ?? '') }}"
                   class="form-control @error('kepakaran') is-invalid @enderror"
                   placeholder="mis. Manajemen Keuangan, Pasar Modal, Perbankan">
            <small class="form-hint">Bidang keahlian. Pisahkan dengan koma — tampil sebagai label di halaman dosen.</small>
            @error('kepakaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

<div class="mb-3">
    <label class="form-label">Biografi</label>
    <textarea id="editor-biografi" name="biografi" rows="8"
              class="form-control @error('biografi') is-invalid @enderror">{{ old('biografi', $d->biografi ?? '') }}</textarea>
    <small class="form-hint">Riwayat pendidikan, jabatan, keahlian, dll. Tampil di halaman detail dosen.</small>
    @error('biografi') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
</div>

{{-- Tautan profil default (Scholar, SINTA, ORCID) — admin cukup isi URL --}}
<div class="mb-3">
    <label class="form-label">Tautan Profil</label>
    <div class="form-hint mb-2">Tombol otomatis tampil di halaman dosen bila URL diisi.</div>
    <div class="row g-2">
        @foreach (\App\Models\Dosen::profilLinks() as $field => $meta)
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="ti {{ $meta['icon'] }}"></i></span>
                    <input type="url" name="{{ $field }}" value="{{ old($field, $d->$field ?? '') }}"
                           class="form-control @error($field) is-invalid @enderror" placeholder="{{ $meta['label'] }} URL">
                </div>
                @error($field) <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
        @endforeach
    </div>
</div>

{{-- Tautan & tombol kustom (bisa ditambah manual) --}}
<div class="mb-3">
    <label class="form-label">Tautan &amp; Tombol Lain</label>
    <div class="form-hint mb-2">Tombol tautan kustom (mis. Google Scholar, SINTA, Sister). Atur teks, ikon, dan warna. Kosongkan label/URL untuk mengabaikan baris.</div>

    <datalist id="dosenIconList">
        <option value="ti-external-link"><option value="ti-school"><option value="ti-book"><option value="ti-id"><option value="ti-world"><option value="ti-mail"><option value="ti-brand-google"><option value="ti-brand-linkedin"><option value="ti-file-cv"><option value="ti-link">
    </datalist>

    <div id="tautanWrap">
        @foreach (old('tautan', $d->tautan ?? []) as $i => $t)
            @include('admin.dosen._tautan_row', ['i' => $i, 't' => $t])
        @endforeach
    </div>
    <template id="tplTautan">@include('admin.dosen._tautan_row', ['i' => '__I__', 't' => []])</template>
    <button type="button" class="btn btn-sm btn-outline-primary" id="addTautan"><i class="ti ti-plus me-1"></i>Tambah tautan</button>
</div>

<div class="d-flex">
    <a href="{{ route('admin.dosen.index') }}" class="btn btn-link">Batal</a>
    <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
</div>

@include('admin.partials.tinymce', ['selector' => '#editor-biografi', 'height' => 280])

@push('scripts')
<script>
    (function () {
        var map = @json(\App\Models\Dosen::konsentrasiMap());
        var katSel = document.getElementById('kategoriSelect');
        var konSel = document.getElementById('konsentrasiSelect');
        if (!katSel || !konSel) return;

        function refresh() {
            var opsi = map[katSel.value] || [];
            var current = konSel.getAttribute('data-current') || konSel.value;
            konSel.innerHTML = '';

            var blank = document.createElement('option');
            blank.value = '';
            blank.textContent = opsi.length ? '— pilih konsentrasi —' : '— pilih kategori dulu —';
            konSel.appendChild(blank);

            opsi.forEach(function (label) {
                var o = document.createElement('option');
                o.value = label; o.textContent = label;
                if (label === current) o.selected = true;
                konSel.appendChild(o);
            });
            konSel.removeAttribute('data-current');
        }

        katSel.addEventListener('change', refresh);
        refresh();
    })();

    // Repeater tautan/tombol kustom
    (function () {
        var wrap = document.getElementById('tautanWrap');
        var tpl = document.getElementById('tplTautan');
        var add = document.getElementById('addTautan');
        if (!wrap || !tpl || !add) return;
        var seq = 1000;
        add.addEventListener('click', function () {
            wrap.insertAdjacentHTML('beforeend', tpl.innerHTML.replaceAll('__I__', seq++));
        });
        wrap.addEventListener('click', function (e) {
            var d = e.target.closest('[data-del-tautan]');
            if (d) d.closest('.tautan-row').remove();
        });
    })();
</script>
@endpush
