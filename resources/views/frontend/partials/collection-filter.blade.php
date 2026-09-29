<div class="modern-filter">
    <label>{{ $searchLabel }}<input type="search" class="form-control" data-collection-search placeholder="Ketik kata kunci"></label>
    <label>Kategori<select class="form-select" data-collection-category><option value="">Semua kategori</option>@foreach ($filterCategories as $value => $label)<option value="{{ $value }}">{{ $label }}</option>@endforeach</select></label>
    <button type="button" class="btn btn-icon" data-collection-reset aria-label="Reset filter" title="Reset filter"><i class="ti ti-filter-off" aria-hidden="true"></i></button>
</div>
<p class="text-secondary small" data-collection-count role="status"></p>
<div class="empty" data-collection-empty hidden><p class="empty-title">Tidak ada hasil</p><p class="text-secondary">Coba kata kunci atau kategori lain.</p></div>
