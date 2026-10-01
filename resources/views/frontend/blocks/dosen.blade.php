{{-- Blok: Daftar Dosen (dinamis). $block --}}
@php
    $d = $block['data'] ?? [];
    $dosen = \App\Models\Dosen::orderBy('urutan')->get();
@endphp
@if ($dosen->isNotEmpty())
    <section class="section home-dosen">
        <div class="container-xl">
            @include('frontend.partials.section-header', [
                'eyebrow' => $d['eyebrow'] ?? null,
                'title'   => $d['title'] ?? '',
                'link'    => !empty($d['link_label']) ? ['label' => $d['link_label'], 'url' => $d['link_url'] ?: '#'] : null,
            ])
            <div class="dosen-marquee" aria-label="Daftar dosen" style="--dosen-scroll-duration: {{ max(30, $dosen->count() * 6) }}s">
                <div class="dosen-track {{ $dosen->count() > 1 ? 'is-moving' : '' }}">
                    @foreach ([false, true] as $duplicate)
                        @if (! $duplicate || $dosen->count() > 1)
                            <div class="dosen-marquee-group" @if ($duplicate) aria-hidden="true" inert @endif>
                                @foreach ($dosen as $dz)
                                    <div class="dosen-slide">
                                        <a href="{{ route('dosen.show', $dz) }}" class="card card-hover dosen-card dosen-card-sm h-100 text-reset text-decoration-none">
                                            <div class="dosen-photo">
                                                @if ($dz->foto)
                                                    <img src="{{ Storage::url($dz->foto) }}" alt="Foto {{ $dz->nama }}" loading="lazy">
                                                @else
                                                    <span class="ph">{{ \Illuminate\Support\Str::of($dz->nama)->substr(0, 1)->upper() }}</span>
                                                @endif
                                                @if ($dz->jabatan)
                                                    <span class="dosen-role-overlay" aria-label="Jabatan: {{ $dz->jabatan }}">
                                                        <i class="ti ti-briefcase" aria-hidden="true"></i>
                                                        <span class="dosen-role-overlay-text">{{ $dz->jabatan }}</span>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="card-body">
                                                <h3 class="dosen-name fw-bold mb-1">{{ $dz->nama }}</h3>
                                                @if ($dz->konsentrasi)
                                                    @php($km = \App\Models\Dosen::konsentrasiMeta($dz->konsentrasi))
                                                    <span class="badge bg-{{ $km['color'] }}-lt dosen-konsentrasi"><i class="ti {{ $km['icon'] }} me-1"></i>{{ $dz->konsentrasi }}</span>
                                                @endif
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif
