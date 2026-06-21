{{-- Baris tautan/tombol kustom dosen. Params: $i (index), $t (data) --}}
@php($colors = ['primary' => 'Brand', 'blue' => 'Biru', 'azure' => 'Azure', 'green' => 'Hijau', 'teal' => 'Teal', 'orange' => 'Oranye', 'red' => 'Merah', 'purple' => 'Ungu', 'indigo' => 'Indigo', 'dark' => 'Gelap'])
<div class="row g-2 mb-2 align-items-center tautan-row">
    <div class="col-6 col-md-3">
        <input class="form-control form-control-sm" placeholder="Label tombol" name="tautan[{{ $i }}][label]" value="{{ $t['label'] ?? '' }}">
    </div>
    <div class="col-6 col-md-4">
        <input class="form-control form-control-sm" placeholder="https://…" name="tautan[{{ $i }}][url]" value="{{ $t['url'] ?? '' }}">
    </div>
    <div class="col-6 col-md-2">
        <input class="form-control form-control-sm" list="dosenIconList" placeholder="ti-link (ikon)" name="tautan[{{ $i }}][icon]" value="{{ $t['icon'] ?? '' }}">
    </div>
    <div class="col-5 col-md-2">
        <select class="form-select form-select-sm" name="tautan[{{ $i }}][color]">
            @foreach ($colors as $cv => $cl)
                <option value="{{ $cv }}" @selected(($t['color'] ?? 'primary') === $cv)>{{ $cl }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-1 text-end">
        <button type="button" class="btn btn-icon btn-sm btn-ghost-danger" data-del-tautan aria-label="Hapus"><i class="ti ti-x"></i></button>
    </div>
</div>
