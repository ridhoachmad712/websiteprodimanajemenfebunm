{{-- Blok: Tentang / Sambutan. $block --}}
@php($d = $block['data'] ?? [])
@php($hasImg = !empty($d['image_url']))
<section class="section section-tint">
    <div class="container-xl">
        <div class="row align-items-center g-5">
            @if ($hasImg)
                <div class="col-lg-5" data-reveal>
                    <img src="{{ $d['image_url'] }}" class="img-fluid rounded shadow-sm" alt="{{ $d['title'] ?? '' }}" loading="lazy">
                </div>
            @endif
            <div class="col-lg-{{ $hasImg ? '7' : '12' }}" data-reveal>
                @if (!empty($d['eyebrow']))<span class="eyebrow">{{ $d['eyebrow'] }}</span>@endif
                <h2 class="section-title mb-3">{{ $d['title'] ?? '' }}</h2>
                @if (!empty($d['body']))<p class="text-secondary fs-4 mb-4">{{ $d['body'] }}</p>@endif
                @if (!empty($d['items']))
                    <div class="row g-3">
                        @foreach ($d['items'] as $it)
                            @if (!empty($it['text']))
                                <div class="col-sm-6 d-flex align-items-start"><i class="ti ti-circle-check text-primary fs-2 me-2"></i><span>{{ $it['text'] }}</span></div>
                            @endif
                        @endforeach
                    </div>
                @endif
                @if (!empty($d['button_label']))
                    <a href="{{ $d['button_url'] ?: '#' }}" class="btn btn-primary mt-4">{{ $d['button_label'] }} <i class="ti ti-arrow-right ms-1"></i></a>
                @endif
            </div>
        </div>
    </div>
</section>
