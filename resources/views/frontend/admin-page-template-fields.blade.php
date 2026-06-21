@if ($template === 'landing')
    <div class="card mb-3"><div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3"><label class="form-label">Eyebrow</label><input class="form-control" name="template_data[eyebrow]" value="{{ old('template_data.eyebrow', $data['eyebrow'] ?? '') }}"></div>
            <div class="col-md-8 mb-3"><label class="form-label">Judul Hero</label><input class="form-control" name="template_data[hero_title]" value="{{ old('template_data.hero_title', $data['hero_title'] ?? '') }}"></div>
        </div>
        <label class="form-label">Subjudul Hero</label><textarea class="form-control mb-3" name="template_data[hero_subtitle]" rows="2">{{ old('template_data.hero_subtitle', $data['hero_subtitle'] ?? '') }}</textarea>
        <div class="row">
            <div class="col-md-3 mb-3"><label class="form-label">Tombol Utama</label><input class="form-control" name="template_data[primary_label]" value="{{ old('template_data.primary_label', $data['primary_label'] ?? '') }}"></div>
            <div class="col-md-3 mb-3"><label class="form-label">URL Utama</label><input class="form-control" name="template_data[primary_url]" value="{{ old('template_data.primary_url', $data['primary_url'] ?? '') }}"></div>
            <div class="col-md-3 mb-3"><label class="form-label">Tombol Kedua</label><input class="form-control" name="template_data[secondary_label]" value="{{ old('template_data.secondary_label', $data['secondary_label'] ?? '') }}"></div>
            <div class="col-md-3 mb-3"><label class="form-label">URL Kedua</label><input class="form-control" name="template_data[secondary_url]" value="{{ old('template_data.secondary_url', $data['secondary_url'] ?? '') }}"></div>
        </div>
        <label class="form-label">Konten Pendukung</label><textarea class="form-control editor-template" name="template_data[body]" rows="8">{{ old('template_data.body', $data['body'] ?? '') }}</textarea>
        <div class="hr-text">Highlight</div>
        <div id="templateItems">
            @foreach (($data['items'] ?? [[]]) as $i => $item)
                <div class="template-item border rounded p-3 mb-2">
                    <div class="row">
                        <div class="col-md-4 mb-2"><input class="form-control" name="template_data[items][{{ $i }}][title]" value="{{ $item['title'] ?? '' }}" placeholder="Judul highlight"></div>
                        <div class="col-md-7 mb-2"><input class="form-control" name="template_data[items][{{ $i }}][body]" value="{{ $item['body'] ?? '' }}" placeholder="Deskripsi"></div>
                        <div class="col-md-1 mb-2"><button type="button" class="btn btn-icon btn-ghost-danger" data-del-row><i class="ti ti-trash"></i></button></div>
                    </div>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-outline-primary mt-2" data-add-template-item><i class="ti ti-plus me-1"></i>Tambah Highlight</button>
    </div></div>
@endif

@if (in_array($template, ['documents', 'faq', 'timeline'], true))
    <div class="card mb-3"><div class="card-body">
        <label class="form-label">Intro</label>
        <textarea class="form-control editor-template" name="template_data[intro]" rows="6">{{ old('template_data.intro', $data['intro'] ?? '') }}</textarea>
    </div></div>
    <div id="templateItems">
        @foreach (($data['items'] ?? [[]]) as $i => $item)
            <div class="card mb-3 template-item">
                <div class="card-header"><h3 class="card-title">Item</h3><div class="card-actions"><button type="button" class="btn btn-icon btn-sm btn-ghost-danger" data-del-row><i class="ti ti-trash"></i></button></div></div>
                <div class="card-body">
                    @if ($template === 'documents')
                        <div class="row">
                            <div class="col-md-3 mb-2"><input class="form-control" name="template_data[items][{{ $i }}][category]" value="{{ $item['category'] ?? '' }}" placeholder="Kategori"></div>
                            <div class="col-md-3 mb-2"><input class="form-control" name="template_data[items][{{ $i }}][label]" value="{{ $item['label'] ?? '' }}" placeholder="Nama dokumen"></div>
                            <div class="col-md-3 mb-2"><input class="form-control" name="template_data[items][{{ $i }}][url]" value="{{ $item['url'] ?? '' }}" placeholder="URL"></div>
                            <div class="col-md-3 mb-2"><input class="form-control" name="template_data[items][{{ $i }}][description]" value="{{ $item['description'] ?? '' }}" placeholder="Deskripsi"></div>
                        </div>
                        <input type="file" class="form-control" name="template_data[items][{{ $i }}][file]">
                    @elseif ($template === 'faq')
                        <label class="form-label">Pertanyaan</label><input class="form-control mb-2" name="template_data[items][{{ $i }}][question]" value="{{ $item['question'] ?? '' }}">
                        <label class="form-label">Jawaban</label><textarea class="form-control editor-template" name="template_data[items][{{ $i }}][answer]" rows="4">{{ $item['answer'] ?? '' }}</textarea>
                    @else
                        <div class="row">
                            <div class="col-md-3 mb-2"><label class="form-label">Tanggal/Label</label><input class="form-control" name="template_data[items][{{ $i }}][date]" value="{{ $item['date'] ?? '' }}"></div>
                            <div class="col-md-9 mb-2"><label class="form-label">Judul</label><input class="form-control" name="template_data[items][{{ $i }}][title]" value="{{ $item['title'] ?? '' }}"></div>
                        </div>
                        <label class="form-label">Isi</label><textarea class="form-control editor-template" name="template_data[items][{{ $i }}][body]" rows="4">{{ $item['body'] ?? '' }}</textarea>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    <button type="button" class="btn btn-outline-primary mb-3" data-add-template-item><i class="ti ti-plus me-1"></i>Tambah Item</button>
@endif

@if ($template === 'embed')
    <div class="card mb-3"><div class="card-body">
        <label class="form-label">Intro</label><textarea class="form-control editor-template mb-3" name="template_data[intro]" rows="6">{{ old('template_data.intro', $data['intro'] ?? '') }}</textarea>
        <div class="row">
            <div class="col-md-8 mb-3"><label class="form-label">URL Embed</label><input class="form-control" name="template_data[embed_url]" value="{{ old('template_data.embed_url', $data['embed_url'] ?? '') }}"></div>
            <div class="col-md-4 mb-3"><label class="form-label">Tinggi iframe</label><input type="number" min="300" max="1200" class="form-control" name="template_data[height]" value="{{ old('template_data.height', $data['height'] ?? 640) }}"></div>
        </div>
    </div></div>
@endif

@push('scripts')
<script>
    (function () {
        var index = {{ count($data['items'] ?? []) + 1 }};
        document.addEventListener('click', function (e) {
            var add = e.target.closest('[data-add-template-item]');
            if (add) {
                var wrap = document.getElementById('templateItems');
                var last = wrap && wrap.querySelector('.template-item:last-child');
                if (!wrap || !last) return;
                var clone = last.cloneNode(true);
                clone.querySelectorAll('input, textarea').forEach(function (field) {
                    field.name = field.name.replace(/items\]\[\d+\]/, 'items][' + index + ']');
                    if (field.type !== 'file') field.value = '';
                });
                wrap.appendChild(clone);
                index++;
            }
            if (e.target.closest('[data-del-row]')) {
                var row = e.target.closest('.template-item');
                if (row) row.remove();
            }
        });
    })();
</script>
@endpush
