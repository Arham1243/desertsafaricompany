<title>{{ optional($seo)->seo_title ?? (isset($title) ? $title . ' | ' . env('APP_NAME') : env('APP_NAME')) }}</title>
@if ($seo)
    @if (optional($seo)->seo_description)
        <meta name="description" content="{{ optional($seo)->seo_description }}">
    @endif
    <meta name="robots" content="{{ optional($seo)->is_seo_index ? 'index, follow' : 'noindex, nofollow' }}">
    @if (optional($seo)->canonical)
        <link rel="canonical" href="{{ optional($seo)->canonical }}">
    @endif
    @if (optional($seo)->fb_title)
        <meta property="og:title" content="{{ optional($seo)->fb_title }}">
    @endif
    @if (optional($seo)->fb_description)
        <meta property="og:description" content="{{ optional($seo)->fb_description }}">
    @endif
    @if (optional($seo)->fb_featured_image)
        <meta property="og:image" content="{{ asset(optional($seo)->fb_featured_image) }}">
    @endif
    @if (optional($seo)->tw_title)
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ optional($seo)->tw_title }}">
    @endif
    @if (optional($seo)->tw_description)
        <meta name="twitter:description" content="{{ optional($seo)->tw_description }}">
    @endif
    @if (optional($seo)->tw_featured_image)
        <meta name="twitter:image" content="{{ asset(optional($seo)->tw_featured_image) }}">
    @endif
    @if (optional($seo)->schema)
        <script type="application/ld+json">
        {!! optional($seo)->schema !!}
    </script>
    @endif
@endif
