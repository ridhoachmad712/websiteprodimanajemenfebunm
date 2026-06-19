{{-- Header halaman dalam (banner gradient + breadcrumb).
     Variabel: $title, $crumbs (array [label => url|null]), $subtitle (opsional) --}}
<section class="page-hero">
    <div class="container-xl">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-arrows mb-2">
                @foreach ($crumbs as $label => $url)
                    @if ($url && ! $loop->last)
                        <li class="breadcrumb-item"><a href="{{ $url }}">{{ $label }}</a></li>
                    @else
                        <li class="breadcrumb-item active">{{ $label }}</li>
                    @endif
                @endforeach
            </ol>
        </nav>
        <h1 class="mb-0">{{ $title }}</h1>
        @isset($subtitle)
            <p class="mb-0 mt-2" style="color:rgba(255,255,255,.8);max-width:42rem">{{ $subtitle }}</p>
        @endisset
    </div>
</section>
