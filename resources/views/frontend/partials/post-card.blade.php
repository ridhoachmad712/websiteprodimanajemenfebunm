{{-- Kartu berita (modern). Variabel: $post --}}
<div class="card card-hover post-card h-100">
    <a href="{{ $post->url() }}" class="post-thumb d-block">
        @if ($post->featured_image)
            <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->judul }}" loading="lazy">
        @else
            <span class="post-thumb-ph d-flex align-items-center justify-content-center text-white"><i class="ti ti-news"></i></span>
        @endif
    </a>
    <div class="card-body d-flex flex-column">
        @if ($post->categories->isNotEmpty())
            <span class="badge bg-primary post-cat align-self-start">{{ $post->categories->first()->nama }}</span>
        @endif
        <div class="text-secondary small mb-2">
            <i class="ti ti-calendar me-1"></i>{{ $post->published_at?->translatedFormat('d F Y') }}
        </div>
        <h3 class="card-title h4 mb-2">
            <a href="{{ $post->url() }}" class="text-reset text-decoration-none stretched-link">{{ $post->judul }}</a>
        </h3>
        <p class="text-secondary mb-0 excerpt-clamp">{{ $post->ringkasan(100) }}</p>
    </div>
</div>
