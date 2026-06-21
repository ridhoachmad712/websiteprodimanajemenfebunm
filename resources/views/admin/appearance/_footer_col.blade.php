{{-- Kartu satu kolom footer. Params: $i (index), $col --}}
<div class="card mb-2 fcol">
    <div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-2">
            <input class="form-control fw-bold" placeholder="Judul kolom" name="footer_columns[{{ $i }}][title]" value="{{ $col['title'] ?? '' }}">
            <button type="button" class="btn btn-icon btn-ghost-danger flex-shrink-0" data-del-fcol title="Hapus kolom"><i class="ti ti-trash"></i></button>
        </div>
        <div data-flinks>
            @foreach ($col['links'] ?? [] as $j => $l)
                @include('admin.appearance._footer_link', ['i' => $i, 'j' => $j, 'l' => $l])
            @endforeach
        </div>
        <template class="tpl-flink">@include('admin.appearance._footer_link', ['i' => $i, 'j' => '__J__', 'l' => []])</template>
        <button type="button" class="btn btn-sm btn-outline-primary mt-1" data-add-flink><i class="ti ti-plus me-1"></i>Tambah tautan</button>
    </div>
</div>
