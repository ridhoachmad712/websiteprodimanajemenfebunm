{{-- Blok: Fitur / Pilar. $block --}}
@php($d = $block['data'] ?? [])
<section class="section home-pillars">
    <div class="container-xl">
        <div class="home-pillars-layout">
            <div class="home-pillars-intro">
                @if (!empty($d['eyebrow']))<span class="eyebrow">{{ $d['eyebrow'] }}</span>@endif
                <h2 class="section-title">{{ $d['title'] ?? '' }}</h2>
                @if (!empty($d['subtitle']))<p class="section-subtitle mb-0">{{ $d['subtitle'] }}</p>@endif
            </div>
            <div class="home-pillars-list">
                @foreach ($d['items'] ?? [] as $it)
                    <div class="home-pillar">
                        @if (!empty($it['icon']))<span class="feature-icon" aria-hidden="true"><i class="ti {{ $it['icon'] }}"></i></span>@endif
                        <div>
                            <h3 class="home-pillar-title">{{ $it['title'] ?? '' }}</h3>
                            <p class="mb-0">{{ $it['desc'] ?? '' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
