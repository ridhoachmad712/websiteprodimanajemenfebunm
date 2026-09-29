<article class="card post-card pengumuman-card h-100">
    <div class="card-body d-flex flex-column">
        <span class="badge bg-primary post-cat align-self-start">Pengumuman</span>
        <div class="text-secondary small mb-2">
            <i class="ti ti-calendar me-1" aria-hidden="true"></i><time datetime="{{ $p->published_at?->toDateString() }}">{{ $p->published_at?->translatedFormat('d F Y') }}</time>
        </div>
        <h3 class="card-title h4 mb-2">
            <a href="{{ $p->url() }}" class="text-reset text-decoration-none stretched-link">{{ $p->judul }}</a>
        </h3>
        <p class="text-secondary mb-0 excerpt-clamp">{{ $p->ringkasan(120) }}</p>
    </div>
</article>
