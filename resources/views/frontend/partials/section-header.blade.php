{{-- Header section: eyebrow + judul + subjudul.
     Variabel: $eyebrow, $title, $subtitle (opsional), $center (bool), $link (opsional [label,url]) --}}
<div class="section-heading-row d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3 mb-4">
    <div class="section-header mb-0 {{ ($center ?? false) ? 'text-center mx-auto' : '' }}">
        @isset($eyebrow)<span class="eyebrow">{{ $eyebrow }}</span>@endisset
        <h2 class="section-title mb-0">{{ $title }}</h2>
        @isset($subtitle)<p class="section-subtitle mb-0">{{ $subtitle }}</p>@endisset
    </div>
    @isset($link)
        <a href="{{ $link['url'] }}" class="section-text-link flex-shrink-0">{{ $link['label'] }} <i class="ti ti-arrow-right ms-1" aria-hidden="true"></i></a>
    @endisset
</div>
