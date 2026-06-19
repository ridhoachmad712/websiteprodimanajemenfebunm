{{-- Header section: eyebrow + judul + subjudul.
     Variabel: $eyebrow, $title, $subtitle (opsional), $center (bool), $link (opsional [label,url]) --}}
<div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3 mb-4">
    <div class="section-header mb-0 {{ ($center ?? false) ? 'text-center mx-auto' : '' }}">
        @isset($eyebrow)<span class="eyebrow">{{ $eyebrow }}</span>@endisset
        <h2 class="section-title mb-0">{{ $title }}</h2>
        @isset($subtitle)<p class="section-subtitle mb-0">{{ $subtitle }}</p>@endisset
    </div>
    @isset($link)
        <a href="{{ $link['url'] }}" class="btn btn-outline-primary flex-shrink-0">{{ $link['label'] }} <i class="ti ti-arrow-right ms-1"></i></a>
    @endisset
</div>
