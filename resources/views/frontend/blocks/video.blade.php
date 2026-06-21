{{-- Blok: Video YouTube. $block --}}
@php
    $d = $block['data'] ?? [];
    $videoId = $d['video_id'] ?? \App\Http\Controllers\Admin\HomeBuilderController::youtubeId($d['video_url'] ?? '');
    $style = $d['style'] ?? 'tint';
    $sectionClass = match ($style) {
        'dark' => 'section section-dark',
        'normal' => 'section',
        default => 'section section-tint',
    };
@endphp

@if ($videoId)
<section class="{{ $sectionClass }}">
    <div class="container-xl">
        <div class="section-header text-center" data-reveal>
            @if (!empty($d['eyebrow']))<span class="eyebrow">{{ $d['eyebrow'] }}</span>@endif
            @if (!empty($d['title']))<h2 class="section-title">{{ $d['title'] }}</h2>@endif
            @if (!empty($d['subtitle']))<p class="section-subtitle">{{ $d['subtitle'] }}</p>@endif
        </div>

        <div class="mx-auto" style="max-width: 960px" data-reveal>
            <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm bg-dark">
                <iframe
                    src="https://www.youtube-nocookie.com/embed/{{ $videoId }}"
                    title="{{ $d['title'] ?? 'Video' }}"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen
                    loading="lazy"></iframe>
            </div>
            @if (!empty($d['caption']))
                <div class="text-center small mt-3 {{ $style === 'dark' ? 'text-white-50' : 'text-secondary' }}">{{ $d['caption'] }}</div>
            @endif
        </div>
    </div>
</section>
@endif
