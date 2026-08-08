{{-- Blok: Fitur / Pilar. $block --}}
@php($d = $block['data'] ?? [])
<section class="section">
    <div class="container-xl">
        @include('frontend.partials.section-header', [
            'eyebrow'  => $d['eyebrow'] ?? null,
            'title'    => $d['title'] ?? '',
            'subtitle' => $d['subtitle'] ?? null,
            'center'   => (($d['center'] ?? false) == true) || (($d['center'] ?? '') === '1'),
        ])
        <div class="row row-cards justify-content-center">
            @foreach ($d['items'] ?? [] as $it)
                <div class="col-md-4" data-reveal>
                    <div class="card card-hover h-100">
                        <div class="card-body p-4">
                            @if (!empty($it['icon']))<span class="feature-icon fi-{{ ($loop->index % 4) + 1 }} mb-3"><i class="ti {{ $it['icon'] }}"></i></span>@endif
                            <h3 class="mb-1">{{ $it['title'] ?? '' }}</h3>
                            <p class="text-secondary mb-0">{{ $it['desc'] ?? '' }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
