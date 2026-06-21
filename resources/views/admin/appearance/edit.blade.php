@extends('layouts.admin')

@section('title', 'Tampilan')
@section('pretitle', 'Manajemen')
@section('page-title', 'Tampilan: Navbar & Hero')

@php
    // Helper kecil untuk nilai (key pakai titik, input pakai underscore).
    $val = fn ($key) => old(str_replace('.', '_', $key), $v[$key] ?? '');
    $on  = fn ($key) => (string) old(str_replace('.', '_', $key), $v[$key] ?? '0') === '1';
@endphp

@section('content')
    <form method="POST" action="{{ route('admin.appearance.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <div class="row row-cards">
            {{-- ============== WARNA TEMA ============== --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h3 class="card-title"><i class="ti ti-palette me-2"></i>Warna Tema</h3></div>
                    <div class="card-body">
                        <div class="row align-items-end g-3">
                            <div class="col-auto">
                                <label class="form-label">Warna Utama</label>
                                <input type="color" id="themePrimary" name="theme_primary" value="{{ $val('theme.primary') ?: '#1b3a5b' }}" class="form-control form-control-color">
                                <div class="form-hint">Tombol, tautan, aksen.</div>
                            </div>
                            <div class="col-auto">
                                <label class="form-label">Warna Gelap</label>
                                <input type="color" id="themeDark" name="theme_dark" value="{{ $val('theme.dark') ?: '#0e2238' }}" class="form-control form-control-color">
                                <div class="form-hint">Latar gelap: gradien hero, blok pengumuman.</div>
                            </div>
                            <div class="col">
                                <label class="form-label">Preset cepat</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ([
                                        'Navy'   => ['#1b3a5b', '#0e2238'],
                                        'Biru'   => ['#066fd1', '#0b2e5c'],
                                        'Hijau'  => ['#2e7d32', '#14321a'],
                                        'Teal'   => ['#0f766e', '#07332f'],
                                        'Maroon' => ['#8e2434', '#3a0f16'],
                                        'Ungu'   => ['#6d28d9', '#2a1259'],
                                    ] as $nama => $warna)
                                        <button type="button" class="btn btn-sm theme-preset" data-primary="{{ $warna[0] }}" data-dark="{{ $warna[1] }}"
                                                style="background:{{ $warna[0] }};color:#fff;border-color:{{ $warna[0] }}">{{ $nama }}</button>
                                    @endforeach
                                </div>
                                <div class="form-hint">Klik preset untuk mengisi kedua warna, lalu Simpan.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============== NAVBAR ============== --}}
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header"><h3 class="card-title"><i class="ti ti-layout-navbar me-2"></i>Navbar</h3></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4 mb-3">
                                <label class="form-label">Inisial Logo</label>
                                <input type="text" name="navbar_brand_mark" value="{{ $val('navbar.brand_mark') }}" maxlength="3" class="form-control" placeholder="M">
                            </div>
                            <div class="col-8 mb-3">
                                <label class="form-label">Nama Brand</label>
                                <input type="text" name="navbar_brand_text" value="{{ $val('navbar.brand_text') }}" class="form-control" placeholder="Manajemen">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sub-teks Brand</label>
                            <input type="text" name="navbar_brand_subtext" value="{{ $val('navbar.brand_subtext') }}" class="form-control" placeholder="FEB UNM">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Logo Gambar (opsional)</label>
                            <div class="form-hint mb-2">Jika diisi, menggantikan inisial + nama brand. PNG/SVG/JPG, maks 2 MB.</div>
                            @if ($v['navbar.logo'] ?? false)
                                <div class="mb-2 d-flex align-items-center gap-3">
                                    <img src="{{ Storage::url($v['navbar.logo']) }}" alt="Logo" style="height:40px;background:#1b3a5b;border-radius:.4rem;padding:.25rem .5rem">
                                    <label class="form-check"><input type="checkbox" name="navbar_logo_remove" value="1" class="form-check-input"><span class="form-check-label">Hapus logo</span></label>
                                </div>
                            @endif
                            <input type="file" name="navbar_logo" accept="image/*" class="form-control">
                        </div>

                        <hr>

                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="navbar_sticky" value="1" @checked($on('navbar.sticky'))>
                            <span class="form-check-label">Navbar menempel saat di-scroll (sticky)</span>
                        </label>
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="navbar_show_topbar" value="1" @checked($on('navbar.show_topbar'))>
                            <span class="form-check-label">Tampilkan bar atas (kontak &amp; sosmed)</span>
                        </label>
                        <label class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="navbar_menu_center" value="1" @checked($on('navbar.menu_center'))>
                            <span class="form-check-label">Menu rata tengah</span>
                        </label>

                        <label class="form-label">Tombol Header</label>
                        <div class="form-hint mb-2">Tombol di kanan header. Tambah/hapus & atur teks, link, warna.</div>
                        <div id="navBtnWrap">
                            @foreach ($navbarButtons as $i => $b)
                                @include('admin.appearance._nav_button', ['i' => $i, 'b' => $b])
                            @endforeach
                        </div>
                        <template id="tplNavBtn">@include('admin.appearance._nav_button', ['i' => '__I__', 'b' => []])</template>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addNavBtn"><i class="ti ti-plus me-1"></i>Tambah tombol</button>
                    </div>
                </div>
            </div>

            {{-- ============== HERO ============== --}}
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="card-title"><i class="ti ti-photo me-2"></i>Hero (Beranda)</h3>
                        <div class="card-actions">
                            <label class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" name="hero_show" value="1" @checked($on('hero.show'))>
                                <span class="form-check-label">Tampilkan</span>
                            </label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Eyebrow (teks kecil di atas judul)</label>
                            <input type="text" name="hero_eyebrow" value="{{ $val('hero.eyebrow') }}" class="form-control">
                        </div>
                        <div class="row">
                            <div class="col-7 mb-3">
                                <label class="form-label">Judul</label>
                                <input type="text" name="hero_title" value="{{ $val('hero.title') }}" class="form-control">
                            </div>
                            <div class="col-5 mb-3">
                                <label class="form-label">Kata Beraksen</label>
                                <input type="text" name="hero_title_accent" value="{{ $val('hero.title_accent') }}" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subjudul</label>
                            <textarea name="hero_subtitle" rows="3" class="form-control">{{ $val('hero.subtitle') }}</textarea>
                        </div>

                        @php($hh = (int) ($val('hero.height') ?: 0))
                        <div class="mb-3">
                            <label class="form-label">Tinggi Hero: <span id="hhVal">{{ $hh === 0 ? 'Otomatis' : $hh.'px' }}</span></label>
                            <input type="range" min="0" max="1000" step="20" name="hero_height" value="{{ $hh }}" class="form-range"
                                   oninput="document.getElementById('hhVal').textContent = this.value === '0' ? 'Otomatis' : this.value + 'px'">
                            <div class="form-hint">Geser ke 0 untuk tinggi otomatis (sesuai konten). Nilai &gt; 0 mengunci tinggi minimum &amp; memusatkan konten secara vertikal.</div>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Gaya Latar</label>
                                <select name="hero_bg_style" id="heroBgStyle" class="form-select">
                                    <option value="gradient" @selected($val('hero.bg_style') === 'gradient')>Gradien Warna</option>
                                    <option value="image" @selected($val('hero.bg_style') === 'image')>Gambar</option>
                                </select>
                            </div>
                            <div class="col-6 mb-3" data-bg="gradient">
                                <label class="form-label">Warna Latar</label>
                                <input type="color" name="hero_bg_color" value="{{ $val('hero.bg_color') ?: '#1b3a5b' }}" class="form-control form-control-color">
                            </div>
                        </div>

                        <div data-bg="image">
                            <div class="mb-3">
                                <label class="form-label">Gambar Latar</label>
                                @if ($v['hero.bg_image'] ?? false)
                                    <div class="mb-2 d-flex align-items-center gap-3">
                                        <img src="{{ Storage::url($v['hero.bg_image']) }}" alt="Latar hero" style="height:48px;border-radius:.4rem">
                                        <label class="form-check"><input type="checkbox" name="hero_bg_image_remove" value="1" class="form-check-input"><span class="form-check-label">Hapus gambar</span></label>
                                    </div>
                                @endif
                                <input type="file" name="hero_bg_image" accept="image/*" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kegelapan Overlay: <span id="ovVal">{{ $val('hero.overlay') ?: '55' }}</span>%</label>
                                <input type="range" min="0" max="90" name="hero_overlay" value="{{ $val('hero.overlay') ?: '55' }}" class="form-range" oninput="document.getElementById('ovVal').textContent=this.value">
                                <div class="form-hint">Lapisan gelap di atas gambar agar teks tetap terbaca.</div>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-6 mb-2">
                                <label class="form-label">Tombol 1 — Label</label>
                                <input type="text" name="hero_btn1_label" value="{{ $val('hero.btn1_label') }}" class="form-control">
                                <div class="form-hint">Kosongkan untuk menyembunyikan.</div>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Tombol 1 — Link</label>
                                <input type="text" name="hero_btn1_url" value="{{ $val('hero.btn1_url') }}" class="form-control">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Tombol 2 — Label</label>
                                <input type="text" name="hero_btn2_label" value="{{ $val('hero.btn2_label') }}" class="form-control">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Tombol 2 — Link</label>
                                <input type="text" name="hero_btn2_url" value="{{ $val('hero.btn2_url') }}" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============== KARTU STATISTIK HERO ============== --}}
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="ti ti-chart-bar me-2"></i>Kartu Statistik di Hero</h3>
                        <div class="card-actions">
                            <label class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" name="hero_stats_show" value="1" @checked($on('hero.stats_show'))>
                                <span class="form-check-label">Tampilkan</span>
                            </label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Teks Akreditasi</label>
                            <input type="text" name="hero_accreditation" value="{{ $val('hero.accreditation') }}" class="form-control">
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <label class="form-label">Mahasiswa</label>
                                <input type="text" name="statistik_mahasiswa" value="{{ $val('statistik.mahasiswa') }}" class="form-control">
                            </div>
                            <div class="col-4">
                                <label class="form-label">Dosen</label>
                                <input type="text" name="statistik_dosen" value="{{ $val('statistik.dosen') }}" class="form-control">
                            </div>
                            <div class="col-4">
                                <label class="form-label">Konsentrasi</label>
                                <input type="text" name="statistik_konsentrasi" value="{{ $val('statistik.konsentrasi') }}" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============== FOOTER ============== --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h3 class="card-title"><i class="ti ti-layout-bottombar me-2"></i>Footer</h3></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <label class="form-label">Deskripsi singkat (di bawah logo)</label>
                                <textarea name="footer_about" rows="2" class="form-control" placeholder="Opsional — kalimat singkat tentang prodi">{{ $val('footer.about') }}</textarea>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Teks Copyright</label>
                                <input type="text" name="footer_copyright" value="{{ $val('footer.copyright') }}" class="form-control">
                                <div class="form-hint">Pakai <code>{year}</code> untuk tahun otomatis.</div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Tagline (kanan bawah)</label>
                                <input type="text" name="footer_tagline" value="{{ $val('footer.tagline') }}" class="form-control">
                            </div>
                        </div>

                        <hr>
                        <label class="form-label">Kolom Tautan Footer</label>
                        <div class="form-hint mb-2">Kolom pertama footer (logo, kontak, sosial media) terisi otomatis dari Pengaturan. Kolom-kolom berikut diatur di sini.</div>
                        <div class="row" id="fcolWrap">
                            @foreach ($footerColumns as $i => $col)
                                <div class="col-md-4 fcol-wrap">@include('admin.appearance._footer_col', ['i' => $i, 'col' => $col])</div>
                            @endforeach
                        </div>
                        <template id="tplFcol"><div class="col-md-4 fcol-wrap">@include('admin.appearance._footer_col', ['i' => '__I__', 'col' => []])</div></template>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addFcol"><i class="ti ti-plus me-1"></i>Tambah kolom</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3 mb-4">
            <button class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Simpan Tampilan</button>
            <a href="{{ url('/') }}" target="_blank" class="btn btn-link">Lihat hasil di situs</a>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    (function () {
        var sel = document.getElementById('heroBgStyle');
        var toggle = function () {
            document.querySelectorAll('[data-bg]').forEach(function (el) {
                el.style.display = (el.getAttribute('data-bg') === sel.value) ? '' : 'none';
            });
        };
        if (sel) { sel.addEventListener('change', toggle); toggle(); }

        // Preset warna tema → isi kedua color picker.
        document.querySelectorAll('.theme-preset').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var p = document.getElementById('themePrimary');
                var d = document.getElementById('themeDark');
                if (p) p.value = btn.getAttribute('data-primary');
                if (d) d.value = btn.getAttribute('data-dark');
            });
        });

        // Repeater tombol header.
        var navBtnWrap = document.getElementById('navBtnWrap');
        var tplNavBtn = document.getElementById('tplNavBtn');
        var addNavBtn = document.getElementById('addNavBtn');
        var navBtnSeq = 500;
        if (addNavBtn && tplNavBtn && navBtnWrap) {
            addNavBtn.addEventListener('click', function () {
                navBtnWrap.insertAdjacentHTML('beforeend', tplNavBtn.innerHTML.replaceAll('__I__', navBtnSeq++));
            });
        }

        // Repeater footer: kolom + tautan.
        var fcolWrap = document.getElementById('fcolWrap');
        var tplFcol = document.getElementById('tplFcol');
        var addFcol = document.getElementById('addFcol');
        var colSeq = 1000, linkSeq = 100000;
        if (addFcol && tplFcol && fcolWrap) {
            addFcol.addEventListener('click', function () {
                fcolWrap.insertAdjacentHTML('beforeend', tplFcol.innerHTML.replaceAll('__I__', colSeq++));
            });
        }
        document.addEventListener('click', function (e) {
            var delNavBtn = e.target.closest('[data-del-navbtn]');
            if (delNavBtn) { delNavBtn.closest('.navbtn-row').remove(); return; }
            var delCol = e.target.closest('[data-del-fcol]');
            if (delCol && fcolWrap && fcolWrap.contains(delCol)) { delCol.closest('.fcol-wrap').remove(); return; }
            var delLink = e.target.closest('[data-del-flink]');
            if (delLink) { delLink.closest('.fcol-link').remove(); return; }
            var addLink = e.target.closest('[data-add-flink]');
            if (addLink) {
                var body = addLink.closest('.card-body');
                var wrap = body.querySelector('[data-flinks]');
                var itpl = body.querySelector('template.tpl-flink');
                if (wrap && itpl) wrap.insertAdjacentHTML('beforeend', itpl.innerHTML.replaceAll('__J__', linkSeq++));
            }
        });
    })();
</script>
@endpush
