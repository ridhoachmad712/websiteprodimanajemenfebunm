{{-- Blok: Mitra & Kerjasama — logo berjalan otomatis (marquee). $block --}}
@php
    $d = $block['data'] ?? [];
    $items = \App\Models\Mitra::aktif()->orderBy('urutan')->orderBy('nama')->get();
    $grayscale = ($d['grayscale'] ?? '0') === '1';
@endphp
@if ($items->isNotEmpty())
    <section class="section {{ ($d['style'] ?? 'tint') === 'tint' ? 'section-tint' : '' }}">
        <div class="container-xl">
            @include('frontend.partials.section-header', [
                'eyebrow' => $d['eyebrow'] ?? null,
                'title' => $d['title'] ?? 'Mitra & Kerjasama',
                'center' => true,
            ])
        </div>
        <div class="mitra-marquee {{ $grayscale ? 'is-grayscale' : '' }}" aria-label="Logo mitra">
            {{-- Track digandakan dua kali agar perulangan mulus --}}
            <div class="mitra-track">
                @foreach ($items as $m)
                    @include('frontend.partials.mitra-item', ['m' => $m])
                @endforeach
                @foreach ($items as $m)
                    @include('frontend.partials.mitra-item', ['m' => $m, 'aria' => 'true'])
                @endforeach
            </div>
        </div>
    </section>
@endif
