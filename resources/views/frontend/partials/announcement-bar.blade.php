{{-- Banner pengumuman site-wide (dari Pengumuman ber-flag "sorot"). Dapat ditutup pengunjung. --}}
@php($banner = \App\Models\Pengumuman::banner())
@if ($banner)
    <div class="announcement-bar" id="announcementBar" data-id="{{ $banner->id }}" hidden>
        <div class="container-xl d-flex align-items-center gap-2">
            <i class="ti ti-speakerphone flex-shrink-0"></i>
            <a href="{{ $banner->url() }}" class="announcement-link flex-grow-1 text-truncate">
                <strong>Pengumuman:</strong> {{ $banner->judul }}
            </a>
            <a href="{{ $banner->url() }}" class="announcement-cta d-none d-sm-inline">Selengkapnya <i class="ti ti-arrow-right"></i></a>
            <button type="button" class="announcement-close" id="announcementClose" aria-label="Tutup pengumuman"><i class="ti ti-x"></i></button>
        </div>
    </div>
    <script>
        (function () {
            var bar = document.getElementById('announcementBar');
            if (!bar) return;
            var key = 'announcementDismissed';
            try { if (localStorage.getItem(key) === bar.dataset.id) return; } catch (e) {}
            bar.hidden = false;
            var close = document.getElementById('announcementClose');
            if (close) close.addEventListener('click', function () {
                bar.hidden = true;
                try { localStorage.setItem(key, bar.dataset.id); } catch (e) {}
            });
        })();
    </script>
@endif
