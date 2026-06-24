{{-- Google Analytics (GA4) — hanya tampil bila ID diatur & bukan environment lokal. --}}
@php($gaId = trim((string) \App\Models\Setting::get('analytics.ga_id')))
@if ($gaId !== '' && ! app()->environment('local', 'testing'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', @json($gaId));
    </script>
@endif
