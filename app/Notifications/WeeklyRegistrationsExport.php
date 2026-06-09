<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class WeeklyRegistrationsExport extends Notification
{
    use Queueable;

    /**
     * @param  Collection<int, \App\Models\Registration>  $registrations
     */
    public function __construct(
        public Collection $registrations,
        public \DateTimeInterface $periodStart,
        public \DateTimeInterface $periodEnd,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $period = $this->periodStart->format('d.m.Y').' – '.$this->periodEnd->format('d.m.Y');

        $mail = (new MailMessage)
            ->subject('Kolbareal – Neue Anmeldungen ('.$period.')')
            ->markdown('mail.registrations-export', [
                'registrations' => $this->registrations,
                'period' => $period,
            ]);

        if ($this->registrations->isNotEmpty()) {
            $filename = 'anmeldungen-'.$this->periodStart->format('Y-m-d').'-'.$this->periodEnd->format('Y-m-d').'.csv';

            $mail->attachData($this->buildCsv(), $filename, [
                'mime' => 'text/csv',
            ]);
        }

        return $mail;
    }

    /**
     * Build a UTF-8 CSV (with BOM for Excel) of the registrations.
     */
    private function buildCsv(): string
    {
        $handle = fopen('php://temp', 'r+');

        // BOM so Excel reads umlauts correctly.
        fwrite($handle, "\xEF\xBB\xBF");

        fputcsv($handle, ['Datum', 'Vorname', 'Name', 'Strasse', 'PLZ/Ort', 'E-Mail', 'Telefon', 'Wohnungsgrösse'], ';', '"', '');

        foreach ($this->registrations as $registration) {
            fputcsv($handle, [
                $registration->created_at->format('d.m.Y'),
                $registration->first_name,
                $registration->last_name,
                $registration->street,
                $registration->zip_city,
                $registration->email,
                $registration->phone,
                collect($registration->apartment_sizes)->map(fn ($s) => $s.'-Zi.')->implode(', '),
            ], ';', '"', '');
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return $csv;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'count' => $this->registrations->count(),
        ];
    }
}
