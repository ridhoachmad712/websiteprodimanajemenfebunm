{{-- Grid kartu dosen. Variabel: $items (Collection<Dosen>) --}}
<div class="row row-cards row-cols-2 row-cols-md-3 row-cols-lg-4">
    @foreach ($items as $d)
        <div class="col" data-reveal data-collection-item data-category="{{ $d->kategori }}">
            <a href="{{ route('dosen.show', $d) }}" class="card card-hover dosen-card h-100 text-reset text-decoration-none">
                <div class="dosen-photo">
                    @if ($d->foto)
                        <img src="{{ Storage::url($d->foto) }}" alt="Foto {{ $d->nama }}" loading="lazy">
                    @else
                        <span class="ph">{{ Str::of($d->nama)->substr(0, 1)->upper() }}</span>
                    @endif
                </div>
                <div class="card-body">
                    <h3 class="dosen-name fw-bold mb-1">{{ $d->nama }}</h3>
                    @if ($d->jabatan)
                        <div class="dosen-jabatan small fw-semibold text-primary mb-1"><i class="ti ti-briefcase me-1"></i>{{ $d->jabatan }}</div>
                    @endif
                    @if ($d->nip)
                        <div class="text-secondary small mb-1">NIP. {{ $d->nip }}</div>
                    @endif
                    @if ($d->konsentrasi)
                        @php($km = \App\Models\Dosen::konsentrasiMeta($d->konsentrasi))
                        <span class="badge bg-{{ $km['color'] }}-lt dosen-konsentrasi">
                            <i class="ti {{ $km['icon'] }} me-1"></i>{{ $d->konsentrasi }}
                        </span>
                    @endif
                </div>
            </a>
        </div>
    @endforeach
</div>
