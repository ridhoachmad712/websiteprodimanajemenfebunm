{{-- Kartu satu blok beranda. Params: $i, $type, $d (data array), $enabled (bool) --}}
@php($meta = \App\Http\Controllers\Admin\HomeBuilderController::types()[$type] ?? ['label' => $type, 'icon' => 'ti-box'])
<div class="card mb-3 home-block" data-index="{{ $i }}">
    <input type="hidden" name="blocks[{{ $i }}][type]" value="{{ $type }}">
    <div class="card-header">
        <h3 class="card-title"><i class="ti {{ $meta['icon'] }} me-2"></i>{{ $meta['label'] }}</h3>
        <div class="card-actions d-flex align-items-center gap-2">
            <label class="form-check form-switch m-0" title="Aktif/nonaktif">
                <input type="hidden" name="blocks[{{ $i }}][enabled]" value="0">
                <input class="form-check-input" type="checkbox" name="blocks[{{ $i }}][enabled]" value="1" @checked($enabled)>
            </label>
            <button type="button" class="btn btn-icon btn-sm" data-move="up" title="Naik"><i class="ti ti-arrow-up"></i></button>
            <button type="button" class="btn btn-icon btn-sm" data-move="down" title="Turun"><i class="ti ti-arrow-down"></i></button>
            <button type="button" class="btn btn-icon btn-sm btn-ghost-danger" data-del-block title="Hapus blok"><i class="ti ti-trash"></i></button>
        </div>
    </div>
    <div class="card-body">
        @includeIf('admin.home.fields.'.$type, ['i' => $i, 'd' => $d])
    </div>
</div>
