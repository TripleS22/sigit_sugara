@props(['seo'])
@php
    $title = $seo->fullTitle();
    $description = $seo->description ?: \App\Models\Setting::get('default_meta_description');
    $image = $seo->image ?: (\App\Models\Setting::get('default_og_image') ? asset('storage/'.\App\Models\Setting::get('default_og_image')) : null);
    $indexing = \App\Models\Setting::bool('allow_indexing');
@endphp
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta name="robots" content="{{ $indexing ? $seo->robots() : 'noindex,nofollow' }}">
<link rel="canonical" href="{{ $seo->canonicalUrl() }}">
<meta property="og:type" content="{{ $seo->type }}">
<meta property="og:site_name" content="{{ \App\Models\Setting::get('site_name') }}">
<meta property="og:locale" content="id_ID">
<meta property="og:title" content="{{ $seo->ogTitle ?: $title }}">
<meta property="og:description" content="{{ $seo->ogDescription ?: $description }}">
<meta property="og:url" content="{{ $seo->canonicalUrl() }}">
@if ($image)
    <meta property="og:image" content="{{ $image }}">
    <meta name="twitter:card" content="summary_large_image">
@else
    <meta name="twitter:card" content="summary">
@endif
@foreach ($seo->jsonLd() as $schema)
    <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>
@endforeach
