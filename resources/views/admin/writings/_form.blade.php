@php($p = $post ?? null)

@if ($errors->any())
    <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card"><div class="card-body">
            <div class="mb-3">
                <label for="writingTitle" class="form-label required">Judul</label>
                <input id="writingTitle" name="judul" type="text" maxlength="255" value="{{ old('judul', $p?->judul) }}" class="form-control @error('judul') is-invalid @enderror" required>
                @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="writingExcerpt" class="form-label">Ringkasan</label>
                <textarea id="writingExcerpt" name="excerpt" rows="3" maxlength="200" class="form-control @error('excerpt') is-invalid @enderror">{{ old('excerpt', $p?->excerpt) }}</textarea>
                @error('excerpt')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div>
                <label for="editor-konten" class="form-label required">Isi tulisan</label>
                <textarea id="editor-konten" name="konten" rows="16" class="form-control @error('konten') is-invalid @enderror" required>{{ old('konten', $p?->konten) }}</textarea>
                @error('konten')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
        </div></div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-3"><div class="card-body">
            <label for="writingKind" class="form-label required">Jenis tulisan</label>
            <select id="writingKind" name="jenis" class="form-select" required>
                <option value="artikel" @selected(old('jenis', $p?->jenis ?? 'artikel') === 'artikel')>Artikel</option>
                <option value="opini" @selected(old('jenis', $p?->jenis) === 'opini')>Opini</option>
            </select>
        </div></div>
        <div class="card"><div class="card-body">
            <label for="writingImage" class="form-label">Gambar utama</label>
            @if ($p?->featured_image)<img src="{{ Storage::url($p->featured_image) }}" alt="" class="img-fluid mb-2">@endif
            <input id="writingImage" name="featured_image" type="file" accept="image/jpeg,image/png,image/webp" class="form-control @error('featured_image') is-invalid @enderror">
            <small class="form-hint">JPG, PNG, atau WebP. Maksimal 2 MB.</small>
            @error('featured_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div></div>
    </div>
</div>

<div class="d-flex align-items-center gap-2 mt-3">
    <a href="{{ route('admin.writings.index') }}" class="btn btn-link">Batal</a>
    <button type="submit" class="btn btn-primary ms-auto">Simpan draft</button>
</div>

@include('admin.partials.tinymce', ['selector' => '#editor-konten', 'uploadRoute' => route('admin.writings.uploads.image')])
