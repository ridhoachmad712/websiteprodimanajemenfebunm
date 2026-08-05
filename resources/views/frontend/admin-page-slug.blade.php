{{-- Field slug/URL halaman. Variabel: $page --}}
@if ($page->slugEditable())
    <div class="mb-3">
        <label class="form-label required">Slug URL</label>
        <div class="input-group">
            <span class="input-group-text">{{ url('/halaman') }}/</span>
            <input type="text" name="slug" value="{{ old('slug', $page->slug) }}" class="form-control @error('slug') is-invalid @enderror" required>
        </div>
        @error('slug') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        <small class="form-hint">Mengubah slug mengubah URL halaman. Jika halaman ada di menu, perbarui tautannya di Menu Builder.</small>
    </div>
@else
    <div class="mb-3">
        <label class="form-label">URL Halaman</label>
        <input type="text" value="{{ $page->publicUrl() }}" class="form-control" disabled>
        <small class="form-hint">Halaman inti — slug tidak dapat diubah karena terhubung ke rute tetap.</small>
    </div>
@endif
