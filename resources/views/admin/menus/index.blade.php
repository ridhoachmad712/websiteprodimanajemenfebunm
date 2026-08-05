@extends('layouts.admin')

@section('title', 'Menu')
@section('pretitle', 'Manajemen')
@section('page-title', 'Menu Builder')

@section('page-actions')
    <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">
        <i class="ti ti-plus me-1"></i> Tambah Menu
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <p class="text-secondary mb-0">
                    <i class="ti ti-arrows-move me-1"></i>
                    Seret ikon <i class="ti ti-grip-vertical"></i> untuk mengatur urutan menu &amp; sub-menu. Urutan tersimpan otomatis.
                    Untuk memindah item ke induk lain, gunakan tombol <strong>Edit</strong>.
                </p>
                <span id="menuStatus" class="badge bg-green-lt ms-auto" style="display:none"><i class="ti ti-check me-1"></i>Tersimpan</span>
            </div>

            @if ($menus->isNotEmpty())
                <div class="list-group list-group-flush menu-sortable" data-parent="root">
                    @foreach ($menus as $menu)
                        @include('admin.menus._row', ['item' => $menu, 'level' => 0])
                    @endforeach
                </div>
            @else
                <div class="text-secondary py-3">Belum ada menu.</div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
    (function () {
        var lists = document.querySelectorAll('.menu-sortable');
        if (!lists.length || !window.Sortable) return;

        var url = @json(route('admin.menus.reorder'));
        var token = @json(csrf_token());
        var statusEl = document.getElementById('menuStatus');
        var timer = null;

        function simpan() {
            // Kumpulkan urutan tiap daftar saudara (level atas & anak tiap induk).
            var items = [];
            document.querySelectorAll('.menu-sortable').forEach(function (list) {
                var pos = 0;
                list.querySelectorAll(':scope > .menu-item').forEach(function (it) {
                    pos++;
                    items.push({ id: it.getAttribute('data-id'), urutan: pos });
                });
            });

            fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                body: JSON.stringify({ items: items }),
            }).then(function (res) {
                if (!res.ok) throw new Error('gagal');
                if (statusEl) {
                    statusEl.style.display = '';
                    clearTimeout(timer);
                    timer = setTimeout(function () { statusEl.style.display = 'none'; }, 2000);
                }
            }).catch(function () {
                alert('Gagal menyimpan urutan. Coba lagi.');
            });
        }

        lists.forEach(function (el) {
            new Sortable(el, {
                handle: '.menu-drag',
                draggable: '.menu-item',
                animation: 150,
                fallbackOnBody: true,
                onEnd: simpan,
            });
        });
    })();
</script>
@endpush
