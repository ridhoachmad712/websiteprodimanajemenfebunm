{{-- Form bersama create & edit berita. Variabel: $post (opsional), $categories, $selected (array id) --}}
@php($p = $post ?? null)
@php($selected = $selected ?? old('categories', []))

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label required">Judul</label>
                    <input type="text" name="judul" value="{{ old('judul', $p->judul ?? '') }}"
                           class="form-control @error('judul') is-invalid @enderror" required>
                    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Ringkasan (excerpt)</label>
                    <textarea name="excerpt" rows="2" class="form-control @error('excerpt') is-invalid @enderror"
                              placeholder="Ringkasan singkat untuk kartu & SEO">{{ old('excerpt', $p->excerpt ?? '') }}</textarea>
                    @error('excerpt') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-1">
                    <label class="form-label required">Isi berita</label>
                    <textarea id="editor-konten" name="konten" rows="14"
                              class="form-control @error('konten') is-invalid @enderror">{{ old('konten', $p->konten ?? '') }}</textarea>
                    @error('konten') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header"><h3 class="card-title">Publikasi</h3></div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft" @selected(old('status', $p->status ?? 'draft') === 'draft')>Draft</option>
                        <option value="published" @selected(old('status', $p->status ?? '') === 'published')>Terbit</option>
                    </select>
                </div>
                <div class="mb-0">
                    <label class="form-label">Tanggal terbit</label>
                    <input type="datetime-local" name="published_at"
                           value="{{ old('published_at', optional($p->published_at ?? null)->format('Y-m-d\TH:i')) }}"
                           class="form-control @error('published_at') is-invalid @enderror">
                    <small class="form-hint">Kosongkan → otomatis "sekarang" saat status Terbit.</small>
                    @error('published_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><h3 class="card-title">Kategori</h3></div>
            <div class="card-body">
                @foreach ($categories as $c)
                    <label class="form-check">
                        <input type="checkbox" name="categories[]" value="{{ $c->id }}" class="form-check-input"
                               @checked(in_array($c->id, $selected))>
                        <span class="form-check-label">{{ $c->nama }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><h3 class="card-title">Gambar utama</h3></div>
            <div class="card-body">
                @if ($p && $p->featured_image)
                    <img src="{{ Storage::url($p->featured_image) }}" class="rounded mb-2 img-fluid" alt="">
                @endif
                <input type="file" name="featured_image" accept="image/*"
                       class="form-control @error('featured_image') is-invalid @enderror">
                <small class="form-hint">JPG/PNG/WebP, maks 2 MB.</small>
                @error('featured_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body d-flex">
        <a href="{{ route('admin.posts.index') }}" class="btn btn-link">Batal</a>
        <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#editor-konten',
            height: 460,
            menubar: false,
            plugins: 'lists link image table code autolink',
            toolbar: 'undo redo | blocks | bold italic | bullist numlist | link image table | code',
            branding: false,
            promotion: false,
        });
    </script>
@endpush
