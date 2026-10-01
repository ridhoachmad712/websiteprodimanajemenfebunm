{{-- Footer publik 4 kolom — kontak, sosmed & identitas dari tabel settings. --}}
@php
    $kontakAlamat  = \App\Models\Setting::get('kontak.alamat');
    $kontakTelepon = \App\Models\Setting::get('kontak.telepon');
    $kontakEmail   = \App\Models\Setting::get('kontak.email');
    $ig = \App\Models\Setting::get('sosmed.instagram');
    $tt = \App\Models\Setting::get('sosmed.tiktok');
    $fb = \App\Models\Setting::get('sosmed.facebook');
    $logo = \App\Models\Setting::get('navbar.logo');
    $namaSitus = \App\Models\Setting::get('site.nama', 'Prodi Manajemen FEB UNM');
    $footerAbout = \App\Models\Setting::get('footer.about');
    $footerCols = \App\Http\Controllers\Admin\AppearanceController::footerColumns();
    $footerCopyright = str_replace('{year}', date('Y'), \App\Models\Setting::get('footer.copyright', '© {year} '.$namaSitus.'. Hak cipta dilindungi.'));
    $footerTagline = \App\Models\Setting::get('footer.tagline', 'Forever in Brotherhood · Build — Manage — Integrate');
@endphp
<footer class="footer mt-auto bg-dark text-white-50 d-print-none">
    <div class="container-xl py-5">
        <div class="row g-4">
            {{-- Kolom 1: Identitas & kontak --}}
            <div class="col-12 col-md-6 col-lg-3">
                <p class="footer-name">{{ $namaSitus }}</p>
                @if ($footerAbout) <p class="mb-3">{{ $footerAbout }}</p> @endif
                @if ($kontakAlamat) <p class="mb-2">{{ $kontakAlamat }}</p> @endif
                @if ($kontakTelepon) <p class="mb-1"><i class="ti ti-phone me-1"></i> {{ $kontakTelepon }}</p> @endif
                @if ($kontakEmail) <p class="mb-3"><i class="ti ti-mail me-1"></i> {{ $kontakEmail }}</p> @endif
                <div class="d-flex gap-2">
                    @if ($ig && trim($ig) !== '#') <a href="{{ $ig }}" class="btn btn-icon btn-dark" target="_blank" rel="noopener" aria-label="Instagram"><i class="ti ti-brand-instagram"></i></a> @endif
                    @if ($tt && trim($tt) !== '#') <a href="{{ $tt }}" class="btn btn-icon btn-dark" target="_blank" rel="noopener" aria-label="TikTok"><i class="ti ti-brand-tiktok"></i></a> @endif
                    @if ($fb && trim($fb) !== '#') <a href="{{ $fb }}" class="btn btn-icon btn-dark" target="_blank" rel="noopener" aria-label="Facebook"><i class="ti ti-brand-facebook"></i></a> @endif
                </div>
            </div>

            {{-- Kolom tautan (dinamis dari admin → Tampilan → Footer) --}}
            @foreach ($footerCols as $col)
                <div class="col-6 col-md-6 col-lg-3">
                    @if (!empty($col['title']))<h3 class="text-white fs-5 mb-3">{{ $col['title'] }}</h3>@endif
                    <ul class="list-unstyled space-y-1">
                        @foreach ($col['links'] ?? [] as $l)
                            @if (!empty($l['url']) && trim($l['url']) !== '#')<li><a class="link-secondary text-decoration-none" href="{{ $l['url'] }}">{{ $l['label'] }}</a></li>@endif
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <hr class="my-4 border-secondary">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <span>{{ $footerCopyright }}</span>
            @if ($footerTagline)<span class="text-secondary">{{ $footerTagline }}</span>@endif
        </div>
    </div>
</footer>
