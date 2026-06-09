@php
	$metaTitle = $title ?? 'Kolbareal Affoltern – Mein Zuhause';
	$metaDescription = $description ?? 'Erstbezug ab Frühling 2027: 29 moderne und grosszügige Mietwohnungen mit 1.5 bis 5.5 Zimmern an der Alten Mühlackerstrasse in Zürich-Affoltern.';
	$metaImage = url('/images/header-visual.jpg');
@endphp
<!DOCTYPE html>
<html lang="de" class="scroll-smooth">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $metaTitle }}</title>
<meta name="description" content="{{ $metaDescription }}">
<link rel="canonical" href="{{ url()->current() }}">
<link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/svg+xml" href="/favicon.svg" />
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
<meta name="apple-mobile-web-app-title" content="Kolbareal" />
<link rel="manifest" href="/site.webmanifest" />

{{-- Open Graph --}}
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ $metaImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale" content="de_CH">

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $metaTitle }}">
<meta name="twitter:description" content="{{ $metaDescription }}">
<meta name="twitter:image" content="{{ $metaImage }}">
@if (filled(config('services.turnstile.site_key')))
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js?onload=onTurnstileLoad" async defer></script>
<script>window.onTurnstileLoad = () => document.dispatchEvent(new Event('turnstile:loaded'));</script>
@endif
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-shell text-ink leading-[1.25] antialiased">
{{ $slot }}
</body>
</html>
