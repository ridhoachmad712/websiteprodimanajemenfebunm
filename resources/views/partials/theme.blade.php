{{-- Override variabel warna Tabler dari pengaturan "Warna Tema" (admin → Tampilan).
     Disisipkan di <head> SETELAH brand.css sehingga menimpa nilai default. --}}
@php
    $primary = \App\Models\Setting::get('theme.primary', '#1b3a5b') ?: '#1b3a5b';
    $dark    = \App\Models\Setting::get('theme.dark', '#0e2238') ?: '#0e2238';

    // hex → "r, g, b" untuk kebutuhan rgba().
    $hex = ltrim($primary, '#');
    if (strlen($hex) === 3) {
        $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
    }
    $rgb = strlen($hex) === 6
        ? hexdec(substr($hex, 0, 2)).', '.hexdec(substr($hex, 2, 2)).', '.hexdec(substr($hex, 4, 2))
        : '27, 58, 91';
@endphp
<style>
    :root {
        --tblr-primary: {{ $primary }};
        --tblr-primary-rgb: {{ $rgb }};
        --brand-blue: {{ $primary }};
        --brand-blue-dark: color-mix(in srgb, {{ $primary }}, #000 18%);
        --brand-navy: {{ $dark }};
    }
</style>
