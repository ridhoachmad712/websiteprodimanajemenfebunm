{{-- Blok: Ajakan (CTA). $block --}}
@php($d = $block['data'] ?? [])
<section class="section">
    <div class="container-xl">
        <div class="card border-0 text-white overflow-hidden" style="background:linear-gradient(135deg,var(--brand-blue),var(--brand-navy))" data-reveal>
            <div class="card-body p-5 text-center">
                <h2 class="text-white mb-2">{{ $d['title'] ?? '' }}</h2>
                @if (!empty($d['subtitle']))<p class="mb-4" style="color:rgba(255,255,255,.8)">{{ $d['subtitle'] }}</p>@endif
                <div class="d-flex flex-wrap gap-2 justify-content-center">
                    @if (!empty($d['btn1_label']))<a href="{{ $d['btn1_url'] ?: '#' }}" class="btn btn-light btn-lg">{{ $d['btn1_label'] }}</a>@endif
                    @if (!empty($d['btn2_label']))<a href="{{ $d['btn2_url'] ?: '#' }}" class="btn btn-outline-light btn-lg">{{ $d['btn2_label'] }}</a>@endif
                </div>
            </div>
        </div>
    </div>
</section>
