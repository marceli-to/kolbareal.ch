<!DOCTYPE html>
<html lang="de" class="scroll-smooth">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $title ?? 'Kolbareal Affoltern – Mein Zuhause' }}</title>
<meta name="description" content="Erstbezug ab Frühling 2027: 29 moderne und grosszügige Mietwohnungen mit 1.5 bis 5.5 Zimmern an der Alten Mühlackerstrasse in Zürich-Affoltern.">
<link rel="icon" href="/favicon.ico" sizes="any">
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
