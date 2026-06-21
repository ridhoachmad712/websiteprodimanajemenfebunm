@extends('layouts.admin')

@section('title', 'Beranda')
@section('pretitle', 'Manajemen')
@section('page-title', 'Penyusun Beranda')

@section('page-actions')
    <div class="btn-list">
        <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-secondary"><i class="ti ti-external-link me-1"></i>Lihat Beranda</a>
        <button type="submit" form="homeBuilder" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>Simpan</button>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-xl-9">
            <form method="POST" action="{{ route('admin.home.update') }}" id="homeBuilder">
                @csrf
                @method('PUT')

                <div id="blocks">
                    @foreach ($blocks as $i => $block)
                        @include('admin.home._block', [
                            'i'       => $i,
                            'type'    => $block['type'] ?? 'richtext',
                            'd'       => $block['data'] ?? [],
                            'enabled' => $block['enabled'] ?? true,
                        ])
                    @endforeach
                </div>

                <div id="blocksEmpty" class="card card-stacked @if (count($blocks)) d-none @endif">
                    <div class="card-body text-center text-secondary py-5">
                        Belum ada blok. Tambahkan blok dari panel di samping.
                    </div>
                </div>
            </form>
        </div>

        {{-- Panel tambah blok --}}
        <div class="col-12 col-xl-3">
            <div class="card position-sticky" style="top:5rem">
                <div class="card-header"><h3 class="card-title">Tambah Blok</h3></div>
                <div class="list-group list-group-flush">
                    @foreach ($types as $key => $meta)
                        <button type="button" class="list-group-item list-group-item-action d-flex align-items-center" data-add-block="{{ $key }}">
                            <i class="ti {{ $meta['icon'] }} me-2"></i>{{ $meta['label'] }}
                        </button>
                    @endforeach
                </div>
                <div class="card-footer text-secondary small">
                    Susun blok dengan tombol panah, aktif/nonaktifkan dengan sakelar, lalu klik <strong>Simpan</strong>.
                </div>
            </div>
        </div>
    </div>

    {{-- Template tiap tipe blok (dipakai saat menambah blok baru) --}}
    @foreach ($types as $key => $meta)
        <template id="tpl-{{ $key }}">@include('admin.home._block', ['i' => '__I__', 'type' => $key, 'd' => [], 'enabled' => true])</template>
    @endforeach
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
(function () {
    var blockIndex = {{ count($blocks) }};
    var itemSeq = 1000000;
    var blocksWrap = document.getElementById('blocks');
    var emptyState = document.getElementById('blocksEmpty');

    var tinyConfig = {
        selector: '.block-richtext',
        height: 320,
        menubar: false,
        plugins: 'lists link image table code autolink',
        toolbar: 'undo redo | blocks | bold italic | bullist numlist | link image table | code',
        branding: false, promotion: false, automatic_uploads: true, paste_data_images: true, file_picker_types: 'image',
        images_upload_handler: function (blobInfo) {
            return new Promise(function (resolve, reject) {
                var fd = new FormData();
                fd.append('file', blobInfo.blob(), blobInfo.filename());
                fetch('{{ route('admin.uploads.image') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: fd,
                }).then(function (res) {
                    if (!res.ok) { return res.json().then(function (j) { reject({ message: (j.message || 'Upload gagal'), remove: true }); }); }
                    return res.json().then(function (j) { j && j.location ? resolve(j.location) : reject({ message: 'Respons tidak valid', remove: true }); });
                }).catch(function () { reject({ message: 'Gagal mengunggah gambar', remove: true }); });
            });
        },
    };

    function initEditors() { if (window.tinymce) tinymce.init(tinyConfig); }
    // Bungkus perubahan struktur DOM: simpan & lepas editor dulu, ubah DOM, init ulang.
    function structural(fn) {
        if (window.tinymce) tinymce.remove();
        fn();
        initEditors();
    }
    function refreshEmpty() { emptyState.classList.toggle('d-none', blocksWrap.children.length > 0); }

    // Tambah blok
    document.querySelectorAll('[data-add-block]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var type = btn.getAttribute('data-add-block');
            var tpl = document.getElementById('tpl-' + type);
            if (!tpl) return;
            var html = tpl.innerHTML.replaceAll('__I__', blockIndex++);
            structural(function () {
                blocksWrap.insertAdjacentHTML('beforeend', html);
                refreshEmpty();
            });
            blocksWrap.lastElementChild.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    });

    // Delegasi aksi di dalam daftar blok
    blocksWrap.addEventListener('click', function (e) {
        var t = e.target;

        var del = t.closest('[data-del-block]');
        if (del) {
            if (!confirm('Hapus blok ini?')) return;
            structural(function () { del.closest('.home-block').remove(); refreshEmpty(); });
            return;
        }

        var mv = t.closest('[data-move]');
        if (mv) {
            var card = mv.closest('.home-block');
            var dir = mv.getAttribute('data-move');
            structural(function () {
                if (dir === 'up' && card.previousElementSibling) {
                    card.parentNode.insertBefore(card, card.previousElementSibling);
                } else if (dir === 'down' && card.nextElementSibling) {
                    card.parentNode.insertBefore(card.nextElementSibling, card);
                }
            });
            return;
        }

        var addItem = t.closest('[data-add-item]');
        if (addItem) {
            var body = addItem.closest('.card-body');
            var wrap = body.querySelector('[data-items]');
            var itpl = body.querySelector('template.tpl-item');
            if (wrap && itpl) wrap.insertAdjacentHTML('beforeend', itpl.innerHTML.replaceAll('__J__', itemSeq++));
            return;
        }

        var delItem = t.closest('[data-del-item]');
        if (delItem) { delItem.closest('.item-row').remove(); return; }
    });

    // Pastikan konten editor tersimpan ke textarea sebelum submit.
    document.getElementById('homeBuilder').addEventListener('submit', function () {
        if (window.tinymce) tinymce.triggerSave();
    });

    initEditors();
})();
</script>
@endpush
