{{-- Blok: Konten Bebas (HTML sudah disanitasi saat disimpan). $block --}}
@php($d = $block['data'] ?? [])
@if (!empty($d['html']))
    <section class="section">
        <div class="container-xl">
            <div class="markdown">{!! $d['html'] !!}</div>
        </div>
    </section>
@endif
