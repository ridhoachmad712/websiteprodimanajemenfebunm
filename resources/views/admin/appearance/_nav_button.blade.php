{{-- Baris tombol header. Params: $i (index), $b --}}
@php($colors = ['primary' => 'Brand (solid)', 'outline-primary' => 'Brand (outline)', 'blue' => 'Biru', 'green' => 'Hijau', 'red' => 'Merah', 'orange' => 'Oranye', 'purple' => 'Ungu', 'dark' => 'Gelap', 'light' => 'Terang'])
<div class="row g-2 mb-2 align-items-center navbtn-row">
    <div class="col-4"><input class="form-control form-control-sm" placeholder="Label tombol" name="navbar_buttons[{{ $i }}][label]" value="{{ $b['label'] ?? '' }}"></div>
    <div class="col-4"><input class="form-control form-control-sm" placeholder="URL (/hubungi-kami)" name="navbar_buttons[{{ $i }}][url]" value="{{ $b['url'] ?? '' }}"></div>
    <div class="col-3">
        <select class="form-select form-select-sm" name="navbar_buttons[{{ $i }}][color]">
            @foreach ($colors as $cv => $cl)
                <option value="{{ $cv }}" @selected(($b['color'] ?? 'primary') === $cv)>{{ $cl }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-1 text-end"><button type="button" class="btn btn-icon btn-sm btn-ghost-danger" data-del-navbtn aria-label="Hapus"><i class="ti ti-x"></i></button></div>
</div>
