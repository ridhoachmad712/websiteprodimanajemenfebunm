{{-- Kartu berita. Variabel: $post --}}
<div class="card card-sm h-100">
    @if ($post->featured_image)
        <a href="{{ $post->url() }}" class="d-block">
            <img src="{{ Storage::url($post->featured_image) }}" class="card-img-top" alt="{{ $post->judul }}" loading="lazy" style="aspect-ratio:16/9;object-fit:cover">
        </a>
    @endif
    <div class="card-body d-flex flex-column">
        <div class="mb-2">
            @foreach ($post->categories as $c)
                <a href="{{ route('post.category', $c) }}" class="badge bg-primary-lt text-decoration-none">{{ $c->nama }}</a>
            @endforeach
        </div>
        <h3 class="card-title mb-1">
            <a href="{{ $post->url() }}" class="text-reset text-decoration-none">{{ $post->judul }}</a>
        </h3>
        <div class="text-secondary small mb-2">
            <i class="ti ti-calendar me-1"></i>{{ $post->published_at?->translatedFormat('d F Y') }}
        </div>
        @if ($post->excerpt)
            <p class="text-secondary mb-0">{{ Str::limit($post->excerpt, 110) }}</p>
        @endif
    </div>
</div>
