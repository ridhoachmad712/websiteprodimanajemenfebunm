{{-- Kartu berita. Variabel: $post --}}
<div class="card card-hover h-100">
    <a href="{{ $post->url() }}" class="d-block">
        @if ($post->featured_image)
            <img src="{{ Storage::url($post->featured_image) }}" class="card-img-top" alt="{{ $post->judul }}" loading="lazy" style="aspect-ratio:16/9;object-fit:cover">
        @else
            <div class="card-img-top d-flex align-items-center justify-content-center text-white"
                 style="aspect-ratio:16/9;background:linear-gradient(135deg,var(--brand-blue),var(--brand-navy))">
                <i class="ti ti-news" style="font-size:2.5rem;opacity:.7"></i>
            </div>
        @endif
    </a>
    <div class="card-body d-flex flex-column">
        <div class="mb-2">
            @foreach ($post->categories as $c)
                <a href="{{ route('post.category', $c) }}" class="badge bg-primary-lt text-decoration-none">{{ $c->nama }}</a>
            @endforeach
        </div>
        <h3 class="card-title mb-1">
            <a href="{{ $post->url() }}" class="text-reset text-decoration-none stretched-link">{{ $post->judul }}</a>
        </h3>
        <div class="text-secondary small mb-2">
            <i class="ti ti-calendar me-1"></i>{{ $post->published_at?->translatedFormat('d F Y') }}
        </div>
        @if ($post->excerpt)
            <p class="text-secondary mb-0">{{ Str::limit($post->excerpt, 110) }}</p>
        @endif
    </div>
</div>
