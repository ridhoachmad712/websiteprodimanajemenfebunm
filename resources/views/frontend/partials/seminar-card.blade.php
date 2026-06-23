{{-- Kartu seminar. Variabel: $s (Seminar), $upcoming (bool) --}}
@php($color = \App\Models\Seminar::jenisColor($s->jenis))
<div class="col-md-6 col-lg-4">
    <div class="card h-100 {{ $upcoming ? '' : 'opacity-75' }}">
        <div class="card-body d-flex flex-column">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="badge bg-{{ $color }}-lt">{{ $s->jenisLabel() }}</span>
                <span class="text-secondary small"><i class="ti ti-calendar-event me-1"></i>{{ $s->tanggal->translatedFormat('d M Y') }}</span>
            </div>
            <div class="fw-bold">{{ $s->nama }}</div>
            @if ($s->nim)<div class="text-secondary small mb-1">NIM. {{ $s->nim }}</div>@endif
            <p class="text-secondary small mb-2">{{ $s->judul }}</p>
            <div class="text-muted small mt-auto">
                <div><i class="ti ti-clock me-1"></i>{{ $s->tanggal->translatedFormat('H:i') }} WITA @if ($s->tempat)<span class="ms-2"><i class="ti ti-map-pin me-1"></i>{{ $s->tempat }}</span>@endif</div>
                @if ($s->pembimbing)<div class="mt-1"><i class="ti ti-user-check me-1"></i>{{ $s->pembimbing }}</div>@endif
            </div>
        </div>
    </div>
</div>
