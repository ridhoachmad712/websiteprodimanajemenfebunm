{{-- Blok: Band Statistik (angka beranimasi). $block --}}
@php
    $d = $block['data'] ?? [];
    $items = array_values(array_filter($d['items'] ?? [], fn ($it) => is_array($it) && filled($it['value'] ?? null)));
    $style = $d['style'] ?? 'dark';
@endphp
@if (! empty($items))
    <section class="section stat-band {{ $style === 'tint' ? 'section-tint' : '' }} {{ $style === 'dark' ? 'section-dark' : '' }}">
        <div class="container-xl">
            @if (! empty($d['eyebrow']) || ! empty($d['title']))
                @include('frontend.partials.section-header', [
                    'eyebrow' => $d['eyebrow'] ?? null,
                    'title' => $d['title'] ?? '',
                    'center' => true,
                ])
            @endif

            <div class="row text-center justify-content-center g-4">
                @foreach ($items as $it)
                    <div class="col-6 col-md-3" data-reveal>
                        <div class="stat-item">
                            <div class="stat-icon"><i class="ti {{ $it['icon'] ?? 'ti-chart-bar' }}"></i></div>
                            <div class="stat-value">
                                @if (is_numeric($it['value']))
                                    <span data-count="{{ (int) $it['value'] }}">0</span><span class="plus">{{ $it['suffix'] ?? '' }}</span>
                                @else
                                    {{ $it['value'] }}<span class="plus">{{ $it['suffix'] ?? '' }}</span>
                                @endif
                            </div>
                            <div class="stat-label text-secondary">{{ $it['label'] ?? '' }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
