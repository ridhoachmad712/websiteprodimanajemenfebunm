{{-- Tombol bagikan. Variabel: $url (absolut), $judul --}}
@php
    $u = urlencode($url);
    $t = urlencode($judul);
@endphp
<div class="d-flex align-items-center flex-wrap gap-2 mt-4" data-share>
    <span class="text-secondary me-1"><i class="ti ti-share me-1"></i>Bagikan:</span>
    <a href="https://wa.me/?text={{ $t }}%20{{ $u }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-success" aria-label="Bagikan ke WhatsApp"><i class="ti ti-brand-whatsapp"></i></a>
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $u }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary" aria-label="Bagikan ke Facebook"><i class="ti ti-brand-facebook"></i></a>
    <a href="https://twitter.com/intent/tweet?url={{ $u }}&text={{ $t }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-dark" aria-label="Bagikan ke X"><i class="ti ti-brand-x"></i></a>
    <a href="https://t.me/share/url?url={{ $u }}&text={{ $t }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-info" aria-label="Bagikan ke Telegram"><i class="ti ti-brand-telegram"></i></a>
    <button type="button" class="btn btn-sm btn-outline-secondary" data-copy-link="{{ $url }}" aria-label="Salin tautan"><i class="ti ti-link me-1"></i><span>Salin tautan</span></button>
</div>
<script>
    (function () {
        document.querySelectorAll('[data-copy-link]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var url = btn.getAttribute('data-copy-link');
                var label = btn.querySelector('span');
                var done = function () { if (label) { var o = label.textContent; label.textContent = 'Tersalin!'; setTimeout(function () { label.textContent = o; }, 1500); } };
                if (navigator.clipboard) { navigator.clipboard.writeText(url).then(done).catch(done); }
                else { var ta = document.createElement('textarea'); ta.value = url; document.body.appendChild(ta); ta.select(); try { document.execCommand('copy'); } catch (e) {} document.body.removeChild(ta); done(); }
            });
        });
    })();
</script>
