{{-- Blok: Ajakan (CTA). $block --}}
@php($d = $block['data'] ?? [])
<section class="section section-dark home-cta">
    <div class="container-xl">
        <div class="home-cta-layout">
            <div>
                <h2 class="mb-2">{{ $d['title'] ?? '' }}</h2>
                @if (!empty($d['subtitle']))<p class="mb-0">{{ $d['subtitle'] }}</p>@endif
            </div>
            <div class="home-cta-actions">
                @if (!empty($d['btn1_label']) && !empty($d['btn1_url']))<a href="{{ $d['btn1_url'] }}" class="btn btn-light btn-lg">{{ $d['btn1_label'] }}</a>@endif
                @if (!empty($d['btn2_label']) && !empty($d['btn2_url']))<a href="{{ $d['btn2_url'] }}" class="btn btn-outline-light btn-lg">{{ $d['btn2_label'] }}</a>@endif
            </div>
        </div>
    </div>
</section>
