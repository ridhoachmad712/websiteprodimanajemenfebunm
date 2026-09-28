{{-- Blok: Daftar Postingan (dinamis). $block --}}
@php
    $d = $block['data'] ?? [];
    $count = (int) ($d['count'] ?? 3) ?: 3;
    $cat = $d['category'] ?? '';
    $category = $cat ? \App\Models\Category::where('slug', $cat)->first() : null;
    $query = $category ? $category->posts()->published() : \App\Models\Post::published();
    $posts = $query->with('categories')->latest('published_at')->take($count)->get();
    $style = $d['style'] ?? 'normal';
@endphp
@if ($posts->isNotEmpty())
    @if ($style === 'list')
        {{-- Gaya minimalis: daftar bersih (tanpa gambar), cocok untuk pengumuman. --}}
        <section class="section">
            <div class="container-xl">
                @include('frontend.partials.section-header', [
                    'eyebrow' => $d['eyebrow'] ?? null,
                    'title' => $d['title'] ?? '',
                    'link' => ! empty($d['link_label']) ? ['label' => $d['link_label'], 'url' => $d['link_url'] ?: '#'] : null,
                ])
                <div class="row justify-content-center">
                    <div class="col-lg-9">
                        <div class="card">
                            <div class="list-group list-group-flush">
                                @foreach ($posts as $post)
                                    <a href="{{ $post->url() }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3" data-reveal>
                                        <div class="text-center flex-shrink-0" style="width:52px">
                                            <div class="fw-bold lh-1 text-primary" style="font-size:1.5rem">{{ $post->published_at?->translatedFormat('d') }}</div>
                                            <div class="small text-uppercase text-secondary">{{ $post->published_at?->translatedFormat('M Y') }}</div>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="fw-semibold text-reset">{{ $post->judul }}</div>
                                            <div class="text-secondary small excerpt-clamp mb-0">{{ $post->ringkasan(110) }}</div>
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
    @else
        <section class="section home-posts {{ $style === 'tint' ? 'section-tint' : '' }} {{ $style === 'dark' ? 'section-dark' : '' }}">
            <div class="container-xl">
                @include('frontend.partials.section-header', [
                    'eyebrow' => $d['eyebrow'] ?? null,
                    'title' => $d['title'] ?? '',
                    'link' => ! empty($d['link_label']) ? ['label' => $d['link_label'], 'url' => $d['link_url'] ?: '#'] : null,
                ])
                <div class="row row-cards">
                    @foreach ($posts as $post)
                        <div class="col-md-6 {{ $style === 'normal' && $posts->count() === 3 ? ($loop->first ? 'col-lg-12 home-posts-lead' : 'col-lg-6') : 'col-lg-4' }}">
                            @include('frontend.partials.post-card')
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endif
