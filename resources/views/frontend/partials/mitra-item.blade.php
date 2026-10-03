{{-- Satu item logo mitra. Variabel: $m (Mitra), $aria ('true' untuk duplikat → sembunyikan dari screen reader) --}}
@php($content = $m->logo
    ? '<img src="'.e(\Illuminate\Support\Facades\Storage::url($m->logo)).'" alt="'.e($m->nama).'" loading="lazy">'
    : '<span class="mitra-name">'.e($m->nama).'</span>')
<div class="mitra-item" @if(($aria ?? '') === 'true') aria-hidden="true" inert @endif>
    @if ($m->url)
        <a href="{{ $m->url }}" target="_blank" rel="noopener" title="{{ $m->nama }}">{!! $content !!}</a>
    @else
        {!! $content !!}
    @endif
</div>
