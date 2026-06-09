<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), ['ar']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Dynamic SEO --}}
    <title inertia>{{ \App\Models\Setting::get('site_name', config('app.name')) }}</title>
    <meta name="description" content="{{ \App\Models\Setting::get('meta_description', '') }}">
    <meta name="keywords" content="{{ \App\Models\Setting::get('meta_keywords', '') }}">

    {{-- Open Graph --}}
    <meta property="og:site_name" content="{{ \App\Models\Setting::get('site_name', config('app.name')) }}">
    <meta property="og:locale" content="{{ app()->getLocale() }}">
    @if(\App\Models\Setting::get('logo'))
    <meta property="og:image" content="{{ \Illuminate\Support\Facades\Storage::disk('public')->url(\App\Models\Setting::get('logo')) }}">
    @endif

    {{-- Favicon --}}
    @if(\App\Models\Setting::get('favicon'))
    <link rel="icon" href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url(\App\Models\Setting::get('favicon')) }}">
    @else
    <link rel="icon" href="/favicon.ico">
    @endif

    {{-- Fonts preload --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    @routes
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @inertiaHead
</head>
<body class="font-sans antialiased">
    @inertia
</body>
</html>
