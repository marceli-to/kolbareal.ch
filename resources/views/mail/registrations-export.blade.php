<x-mail::message>
# Neue Anmeldungen

@if ($registrations->isEmpty())
Im Zeitraum **{{ $period }}** sind keine neuen Anmeldungen eingegangen.
@else
Im Zeitraum **{{ $period }}** {{ $registrations->count() === 1 ? 'ist' : 'sind' }} **{{ $registrations->count() }}** neue {{ $registrations->count() === 1 ? 'Anmeldung' : 'Anmeldungen' }} eingegangen.

Die vollständige Liste finden Sie in der angehängten CSV-Datei.
@endif

Diese Liste wird wöchentlich automatisch versendet.

Freundliche Grüsse<br>
{{ config('app.name') }}
</x-mail::message>
