@extends('layouts.admin')

@section('title', 'Manajer Media')
@section('pretitle', 'Sistem')
@section('page-title', 'Manajer Media')

@section('content')
    <div class="card">
        <div class="card-body border-bottom py-3">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-auto">
                    <select name="folder" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua folder ({{ $total }})</option>
                        @foreach ($folders as $f)
                            <option value="{{ $f }}" @selected(request('folder') === $f)>{{ $f }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col"><input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari nama berkas…"></div>
                <div class="col-auto">
                    <button class="btn">Cari</button>
                    @if (request('folder') || request('cari'))<a href="{{ route('admin.media.index') }}" class="btn btn-link">Reset</a>@endif
                </div>
            </form>
        </div>

        <div class="card-body">
            @if ($items->isEmpty())
                <div class="empty">
                    <div class="empty-icon"><i class="ti ti-photo-off fs-1"></i></div>
                    <p class="empty-title">Tidak ada berkas</p>
                    <p class="empty-subtitle text-secondary">Berkas yang diunggah melalui modul akan muncul di sini.</p>
                </div>
            @else
                <div class="row row-cols-2 row-cols-md-4 row-cols-xl-6 g-3">
                    @foreach ($items as $m)
                        <div class="col">
                            <div class="card card-sm h-100">
                                <div class="media-thumb d-flex align-items-center justify-content-center bg-light rounded-top" style="aspect-ratio:1/1;overflow:hidden">
                                    @if ($m['is_image'])
                                        <img src="{{ $m['url'] }}" alt="{{ $m['name'] }}" loading="lazy" style="width:100%;height:100%;object-fit:cover">
                                    @else
                                        <span class="text-secondary text-center"><i class="ti ti-file fs-1 d-block"></i><span class="badge bg-blue-lt mt-1">{{ strtoupper($m['ext']) }}</span></span>
                                    @endif
                                </div>
                                <div class="card-body p-2">
                                    <div class="text-truncate small fw-bold" title="{{ $m['name'] }}">{{ $m['name'] }}</div>
                                    <div class="text-secondary" style="font-size:.7rem">
                                        <span class="badge bg-secondary-lt">{{ $m['folder'] }}</span> · {{ $m['size'] }}
                                    </div>
                                    <div class="btn-list mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary flex-fill" data-copy="{{ $m['url'] }}" title="Salin URL"><i class="ti ti-link"></i></button>
                                        <a href="{{ $m['url'] }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary" title="Buka"><i class="ti ti-external-link"></i></a>
                                        <form method="POST" action="{{ route('admin.media.destroy') }}" onsubmit="return confirm('Hapus berkas {{ $m['name'] }}? Tindakan ini tidak bisa dibatalkan.')">
                                            @csrf @method('DELETE')
                                            <input type="hidden" name="path" value="{{ $m['path'] }}">
                                            <button class="btn btn-sm btn-ghost-danger" title="Hapus"><i class="ti ti-trash"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        @if ($items->hasPages())
            <div class="card-footer d-flex align-items-center">{{ $items->links() }}</div>
        @endif
    </div>

    @push('scripts')
    <script>
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-copy]');
            if (!btn) return;
            var url = new URL(btn.getAttribute('data-copy'), window.location.origin).href;
            var done = function () { btn.classList.add('btn-success'); btn.classList.remove('btn-outline-primary'); setTimeout(function () { btn.classList.remove('btn-success'); btn.classList.add('btn-outline-primary'); }, 1200); };
            if (navigator.clipboard) { navigator.clipboard.writeText(url).then(done).catch(done); } else { done(); }
        });
    </script>
    @endpush
@endsection
