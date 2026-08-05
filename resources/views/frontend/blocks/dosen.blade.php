{{-- Blok: Daftar Dosen (dinamis). $block --}}
@php
    $d = $block['data'] ?? [];
    $count = (int) ($d['count'] ?? 6) ?: 6;
    $dosen = \App\Models\Dosen::orderBy('urutan')->take($count)->get();
@endphp
@if ($dosen->isNotEmpty())
    <section class="section">
        <div class="container-xl">
            @include('frontend.partials.section-header', [
                'eyebrow' => $d['eyebrow'] ?? null,
                'title'   => $d['title'] ?? '',
                'link'    => !empty($d['link_label']) ? ['label' => $d['link_label'], 'url' => $d['link_url'] ?: '#'] : null,
            ])
            <div class="row row-cards row-cols-2 row-cols-md-3 row-cols-lg-6">
                @foreach ($dosen as $dz)
                    <div class="col" data-reveal>
                        <a href="{{ route('dosen.show', $dz) }}" class="card card-hover dosen-card dosen-card-sm h-100 text-reset text-decoration-none">
                            <div class="dosen-photo">
                                @if ($dz->foto)
                                    <img src="{{ Storage::url($dz->foto) }}" alt="Foto {{ $dz->nama }}" loading="lazy">
                                @else
                                    <span class="ph">{{ \Illuminate\Support\Str::of($dz->nama)->substr(0, 1)->upper() }}</span>
                                @endif
                            </div>
                            <div class="card-body">
                                <h3 class="dosen-name fw-bold mb-1">{{ $dz->nama }}</h3>
                                @if ($dz->jabatan)
                                    <div class="dosen-jabatan small fw-semibold text-primary mb-1"><i class="ti ti-briefcase me-1"></i>{{ $dz->jabatan }}</div>
                                @endif
                                @if ($dz->konsentrasi)
                                    @php($km = \App\Models\Dosen::konsentrasiMeta($dz->konsentrasi))
                                    <span class="badge bg-{{ $km['color'] }}-lt dosen-konsentrasi"><i class="ti {{ $km['icon'] }} me-1"></i>{{ $dz->konsentrasi }}</span>
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
