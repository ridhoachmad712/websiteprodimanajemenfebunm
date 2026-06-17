{{-- Form bersama create & edit menu. Variabel: $menu (opsional), $parents --}}
@php($m = $menu ?? null)

@if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label required">Judul</label>
            <input type="text" name="title" value="{{ old('title', $m->title ?? '') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">URL</label>
            <input type="text" name="url" value="{{ old('url', $m->url ?? '') }}" class="form-control"
                   placeholder="/profil  atau  https://…  (kosongkan untuk item induk)">
            <small class="form-hint">Path internal (mis. <code>/berita</code>) atau URL eksternal lengkap. Kosong = hanya pembuka dropdown.</small>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Induk (parent)</label>
                <select name="parent_id" class="form-select">
                    <option value="">— Menu utama (level atas) —</option>
                    @foreach ($parents as $p)
                        <option value="{{ $p->id }}" @selected((string) old('parent_id', $m->parent_id ?? '') === (string) $p->id)>
                            {{ $p->parent_id ? '— ' : '' }}{{ $p->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Target</label>
                <select name="target" class="form-select">
                    <option value="_self" @selected(old('target', $m->target ?? '_self') === '_self')>Tab sama</option>
                    <option value="_blank" @selected(old('target', $m->target ?? '') === '_blank')>Tab baru</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Urutan</label>
                <input type="number" name="urutan" value="{{ old('urutan', $m->urutan ?? 0) }}" min="0" class="form-control">
            </div>
        </div>

        <label class="form-check">
            <input type="hidden" name="aktif" value="0">
            <input type="checkbox" name="aktif" value="1" class="form-check-input" @checked(old('aktif', $m->aktif ?? true))>
            <span class="form-check-label">Aktif (tampil di situs)</span>
        </label>
    </div>
    <div class="card-footer d-flex">
        <a href="{{ route('admin.menus.index') }}" class="btn btn-link">Batal</a>
        <button class="btn btn-primary ms-auto">Simpan</button>
    </div>
</div>
