{{-- Footer publik 4 kolom — kontak, sosmed & identitas dari tabel settings. --}}
@php
    $kontakAlamat  = \App\Models\Setting::get('kontak.alamat');
    $kontakTelepon = \App\Models\Setting::get('kontak.telepon');
    $kontakEmail   = \App\Models\Setting::get('kontak.email');
    $ig = \App\Models\Setting::get('sosmed.instagram');
    $tt = \App\Models\Setting::get('sosmed.tiktok');
    $fb = \App\Models\Setting::get('sosmed.facebook');
@endphp
<footer class="footer mt-auto bg-dark text-white-50 d-print-none">
    <div class="container-xl py-5">
        <div class="row g-4">
            {{-- Kolom 1: Identitas & kontak --}}
            <div class="col-12 col-md-6 col-lg-3">
                <div class="d-flex align-items-center mb-3">
                    <span class="avatar avatar-sm bg-primary text-white me-2">M</span>
                    <span class="fw-bold text-white">{{ \App\Models\Setting::get('site.nama', 'Prodi Manajemen FEB UNM') }}</span>
                </div>
                @if ($kontakAlamat) <p class="mb-2">{{ $kontakAlamat }}</p> @endif
                @if ($kontakTelepon) <p class="mb-1"><i class="ti ti-phone me-1"></i> {{ $kontakTelepon }}</p> @endif
                @if ($kontakEmail) <p class="mb-3"><i class="ti ti-mail me-1"></i> {{ $kontakEmail }}</p> @endif
                <div class="d-flex gap-2">
                    @if ($ig) <a href="{{ $ig }}" class="btn btn-icon btn-dark" target="_blank" rel="noopener" aria-label="Instagram"><i class="ti ti-brand-instagram"></i></a> @endif
                    @if ($tt) <a href="{{ $tt }}" class="btn btn-icon btn-dark" target="_blank" rel="noopener" aria-label="TikTok"><i class="ti ti-brand-tiktok"></i></a> @endif
                    @if ($fb) <a href="{{ $fb }}" class="btn btn-icon btn-dark" target="_blank" rel="noopener" aria-label="Facebook"><i class="ti ti-brand-facebook"></i></a> @endif
                </div>
            </div>

            {{-- Kolom 2: Info Kemahasiswaan --}}
            <div class="col-6 col-md-6 col-lg-3">
                <h3 class="text-white fs-5 mb-3">Info Kemahasiswaan</h3>
                <ul class="list-unstyled space-y-1">
                    <li><a class="link-secondary text-decoration-none" href="#">Pusat Prestasi Nasional</a></li>
                    <li><a class="link-secondary text-decoration-none" href="#">IISMA</a></li>
                    <li><a class="link-secondary text-decoration-none" href="#">Kampus Mengajar</a></li>
                    <li><a class="link-secondary text-decoration-none" href="#">Wirausaha Merdeka</a></li>
                </ul>
            </div>

            {{-- Kolom 3: Sumber Belajar --}}
            <div class="col-6 col-md-6 col-lg-3">
                <h3 class="text-white fs-5 mb-3">Sumber Belajar</h3>
                <ul class="list-unstyled space-y-1">
                    <li><a class="link-secondary text-decoration-none" href="#">Pustaka UNM</a></li>
                    <li><a class="link-secondary text-decoration-none" href="#">OJS UNM</a></li>
                    <li><a class="link-secondary text-decoration-none" href="#">Repositori (eprints)</a></li>
                    <li><a class="link-secondary text-decoration-none" href="#">OER UNM</a></li>
                    <li><a class="link-secondary text-decoration-none" href="#">Thesis UNM</a></li>
                </ul>
            </div>

            {{-- Kolom 4: Tautan Penting --}}
            <div class="col-6 col-md-6 col-lg-3">
                <h3 class="text-white fs-5 mb-3">Tautan Penting</h3>
                <ul class="list-unstyled space-y-1">
                    <li><a class="link-secondary text-decoration-none" href="#">PDDIKTI</a></li>
                    <li><a class="link-secondary text-decoration-none" href="#">UNM</a></li>
                    <li><a class="link-secondary text-decoration-none" href="#">Beasiswa Pendidikan Indonesia</a></li>
                </ul>
            </div>
        </div>

        <hr class="my-4 border-secondary">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <span>&copy; {{ date('Y') }} Program Studi Manajemen FEB UNM. Hak cipta dilindungi.</span>
            <span class="text-secondary">Forever in Brotherhood &middot; Build &mdash; Manage &mdash; Integrate</span>
        </div>
    </div>
</footer>
