{{-- Blok: Pengumuman (modul mandiri). $block --}}
@php
    $d = $block['data'] ?? [];
    $count = (int) ($d['count'] ?? 4) ?: 4;
    $items = \App\Models\Pengumuman::published()->latest('published_at')->take($count)->get();
@endphp
@if ($items->isNotEmpty())
    <section class="section home-announcements">
        <div class="container-xl home-announcements-layout">
            @include('frontend.partials.section-header', [
                'eyebrow' => $d['eyebrow'] ?? null,
                'title' => $d['title'] ?? 'Pengumuman',
                'link' => ['label' => ($d['link_label'] ?? '') ?: 'Semua Pengumuman', 'url' => ($d['link_url'] ?? '') ?: route('pengumuman.index')],
            ])
            <div class="row">
                <div class="col-12">
                    <div class="card home-announcement-list">
                        <div class="list-group list-group-flush">
                            @foreach ($items as $p)
                                <a href="{{ $p->url() }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3" data-reveal>
                                    <div class="text-center flex-shrink-0" style="width:52px">
                                        <div class="fw-bold lh-1 text-primary" style="font-size:1.5rem">{{ $p->published_at?->translatedFormat('d') }}</div>
                                        <div class="small text-uppercase text-secondary">{{ $p->published_at?->translatedFormat('M Y') }}</div>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="fw-semibold text-reset">{{ $p->judul }}</div>
                                        <div class="text-secondary small excerpt-clamp mb-0">{{ $p->ringkasan(110) }}</div>
                                    </div>
                                    <i class="ti ti-chevron-right ms-auto text-muted flex-shrink-0"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
