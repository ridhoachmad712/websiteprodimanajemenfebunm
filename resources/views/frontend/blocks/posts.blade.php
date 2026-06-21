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
    <section class="section {{ $style === 'tint' ? 'section-tint' : '' }} {{ $style === 'dark' ? 'section-dark' : '' }}">
        <div class="container-xl">
            @include('frontend.partials.section-header', [
                'eyebrow' => $d['eyebrow'] ?? null,
                'title'   => $d['title'] ?? '',
                'link'    => !empty($d['link_label']) ? ['label' => $d['link_label'], 'url' => $d['link_url'] ?: '#'] : null,
            ])
            <div class="row row-cards">
                @foreach ($posts as $post)
                    <div class="col-md-6 col-lg-4" data-reveal>
                        @include('frontend.partials.post-card')
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
