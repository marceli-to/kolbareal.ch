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

        return (new MailMessage)
            ->subject('Kolbareal – Neue Anmeldungen ('.$period.')')
            ->markdown('mail.registrations-export', [
                'registrations' => $this->registrations,
                'period' => $period,
            ]);
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
