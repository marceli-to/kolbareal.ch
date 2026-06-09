<x-mail::message>
# Neue Anmeldungen Kolbareal

Im Zeitraum **{{ $period }}** sind **{{ $registrations->count() }}** neue {{ $registrations->count() === 1 ? 'Anmeldung' : 'Anmeldungen' }} eingegangen.

@if ($registrations->isEmpty())
In diesem Zeitraum sind keine neuen Anmeldungen eingegangen.
@else
<x-mail::table>
| Datum | Name | Adresse | Kontakt | Wohnungsgrösse |
| :---- | :--- | :------ | :------ | :------------- |
@foreach ($registrations as $registration)
| {{ $registration->created_at->format('d.m.Y') }} | {{ $registration->fullName() }} | {{ $registration->street }}, {{ $registration->zip_city }} | {{ $registration->email }}@if ($registration->phone)<br>{{ $registration->phone }}@endif | {{ collect($registration->apartment_sizes)->map(fn ($s) => $s.'-Zi.')->implode(', ') }} |
@endforeach
</x-mail::table>
@endif

Diese Liste wird wöchentlich automatisch versendet.

Freundliche Grüsse<br>
{{ config('app.name') }}
</x-mail::message>
