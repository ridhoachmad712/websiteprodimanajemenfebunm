{{-- Editor WYSIWYG TinyMCE dengan dukungan upload gambar.
     Variabel: $selector (CSS selector), $height (opsional) --}}
@once
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js" referrerpolicy="origin"></script>
@endpush
@endonce

@push('scripts')
<script>
    tinymce.init({
        selector: '{{ $selector }}',
        height: {{ $height ?? 460 }},
        menubar: false,
        plugins: 'lists link image table code autolink',
        toolbar: 'undo redo | blocks | bold italic | bullist numlist | link image table | code',
        branding: false,
        promotion: false,
        automatic_uploads: true,
        paste_data_images: true,
        file_picker_types: 'image',
        images_upload_handler: function (blobInfo) {
            return new Promise(function (resolve, reject) {
                var fd = new FormData();
                fd.append('file', blobInfo.blob(), blobInfo.filename());
                fetch('{{ $uploadRoute ?? route('admin.uploads.image') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: fd,
                })
                .then(function (res) {
                    if (!res.ok) { return res.json().then(function (j) { reject({ message: (j.message || 'Upload gagal'), remove: true }); }); }
                    return res.json().then(function (j) {
                        if (j && j.location) { resolve(j.location); }
                        else { reject({ message: 'Respons tidak valid', remove: true }); }
                    });
                })
                .catch(function () { reject({ message: 'Gagal mengunggah gambar', remove: true }); });
            });
        },
    });
</script>
@endpush
