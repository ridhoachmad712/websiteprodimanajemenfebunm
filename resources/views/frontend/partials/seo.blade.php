{{-- Meta SEO + Open Graph untuk halaman publik. --}}
@php
    $metaTitle = trim($__env->yieldContent('title', 'Beranda')).' — '.config('app.name');
    $metaDesc  = trim($__env->yieldContent('meta_description', 'Program Studi Manajemen, Fakultas Ekonomi dan Bisnis, Universitas Negeri Makassar.'));
    $ogImage   = trim($__env->yieldContent('og_image'));
@endphp

<meta name="description" content="{{ $metaDesc }}">
<link rel="canonical" href="{{ url()->current() }}">

{{-- Open Graph --}}
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDesc }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:locale" content="id_ID">
@if ($ogImage)
    <meta property="og:image" content="{{ $ogImage }}">
@endif

{{-- Twitter --}}
<meta name="twitter:card" content="{{ $ogImage ? 'summary_large_image' : 'summary' }}">
<meta name="twitter:title" content="{{ $metaTitle }}">
<meta name="twitter:description" content="{{ $metaDesc }}">
@if ($ogImage)
    <meta name="twitter:image" content="{{ $ogImage }}">
@endif
