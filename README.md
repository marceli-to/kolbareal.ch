# Kolbareal Affoltern

Landing page for the *Kolbareal* residential development (Erstbezug voraussichtlich Herbst 2027) with a
registration form. Built with **Laravel 13**, **Livewire 4**, **Tailwind CSS v4**, **Vite** and
**Alpine** (bundled with Livewire).

Local URL (Herd, SSL): https://kolbareal.ch.test

## Stack & structure

- `resources/views/home.blade.php` — the single landing page (visual, intro, form, map, footer)
- `resources/views/components/layouts/app.blade.php` — base layout
- `resources/views/components/⚡contact-form.blade.php` — Livewire single-file contact form
- `resources/views/components/form/input.blade.php` — shared input field
- `resources/js/modules/map.js` — Mapbox map (Alte Mühlackerstrasse, Zürich-Affoltern)
- `app/Models/Registration.php` + migration — form submissions
- `app/Console/Commands/ExportWeeklyRegistrations.php` — weekly export command
- `app/Notifications/WeeklyRegistrationsExport.php` + `resources/views/mail/registrations-export.blade.php` — markdown email

The design system lives in `resources/css/app.css`: Inter font, brand colors
(`ink #323c46`, `brand #899584`, `shell #ebebee`) and the 1px spacing scale from
`resources/css/spacing.css`.

## Setup

```bash
composer install
npm install
cp .env.example .env   # then fill in the keys below
php artisan key:generate
php artisan migrate
npm run build          # or `npm run dev` while developing
```

## Required keys (provided by the client)

Set these in `.env`:

| Variable | Purpose |
| --- | --- |
| `MAPBOX_TOKEN` | Mapbox GL access token (frontend, exposed via `VITE_MAPBOX_TOKEN`) |
| `TURNSTILE_SITE_KEY` / `TURNSTILE_SECRET_KEY` | Cloudflare Turnstile spam protection |
| `REGISTRATIONS_EXPORT_EMAIL` | Recipient of the weekly registrations email (default `info@kolb-immobilien.ch`) |

Until the Mapbox token is set the map area stays blank; until the Turnstile keys are set the
widget is hidden and the spam check is skipped — so the form is fully testable locally without keys.
Run `npm run build` again after setting Vite-exposed keys (`VITE_*`).

## Weekly registrations export

New submissions are stored in the `registrations` table; no email is sent on submit (the visitor
just sees a confirmation). The accumulated list is emailed to the client weekly.

```bash
php artisan registrations:export-weekly        # send unexported registrations, then mark them exported
php artisan registrations:export-weekly --all  # include everything (re-send)
```

Scheduled every Monday 08:00 Europe/Zurich (`routes/console.php`). On the server, ensure Laravel's
scheduler runs:

```cron
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## Tests

```bash
php artisan test
```
