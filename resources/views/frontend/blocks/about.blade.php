{{-- Blok: Tentang / Sambutan. $block --}}
@php($d = $block['data'] ?? [])
<section class="section home-about">
    <div class="home-about-layout">
        <div class="home-about-copy home-about-details">
            @if (!empty($d['eyebrow']))<span class="eyebrow">{{ $d['eyebrow'] }}</span>@endif
            <h2 class="section-title">{{ $d['title'] ?? '' }}</h2>
            @include('frontend.partials.home-about-details', ['d' => $d])
        </div>
        <div class="home-about-media">
            <img src="{{ ($d['image_url'] ?? '') ?: asset('images/program-studi.jpg') }}" alt="{{ $d['title'] ?? 'Program Studi Manajemen FEB UNM' }}" loading="lazy" width="1560" height="1040">
        </div>
    </div>
</section>
