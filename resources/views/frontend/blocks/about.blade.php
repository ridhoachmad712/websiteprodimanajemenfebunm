{{-- Blok: Tentang / Sambutan. $block --}}
@php($d = $block['data'] ?? [])
@php($hasImg = !empty($d['image_url']))
<section class="section section-tint home-about">
    <div class="container-xl">
        <div class="row align-items-start g-4 g-lg-5">
            @if ($hasImg)
                <div class="col-lg-5" data-reveal>
                    <img src="{{ $d['image_url'] }}" class="img-fluid home-about-image" alt="{{ $d['title'] ?? '' }}" loading="lazy">
                </div>
            @endif
            <div class="col-lg-{{ $hasImg ? '7' : '5' }}">
                @if (!empty($d['eyebrow']))<span class="eyebrow">{{ $d['eyebrow'] }}</span>@endif
                <h2 class="section-title mb-3">{{ $d['title'] ?? '' }}</h2>
                @if ($hasImg)
                    @include('frontend.partials.home-about-details', ['d' => $d])
                @endif
            </div>
            @if (! $hasImg)
            <div class="col-lg-7 home-about-details">
                @include('frontend.partials.home-about-details', ['d' => $d])
            </div>
            @endif
        </div>
    </div>
</section>
