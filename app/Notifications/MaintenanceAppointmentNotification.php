<?php

namespace App\Notifications;

use App\Models\Maintenance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaintenanceAppointmentNotification extends Notification
{
    use Queueable;

    public $maintenance;
    public $timeframe; // '1_month' or '1_week'

    /**
     * Create a new notification instance.
     */
    public function __construct(Maintenance $maintenance, string $timeframe)
    {
        $this->maintenance = $maintenance;
        $this->timeframe = $timeframe;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $timeString = $this->timeframe === '1_month' ? 'één maand' : 'één week';
        $date = $this->maintenance->scheduled_date->format('d-m-Y H:i');

        return (new MailMessage)
            ->subject('Herinnering: Onderhoudsafspraak over ' . $timeString)
            ->greeting('Hallo ' . $notifiable->name . ',')
            ->line('Dit is een herinnering voor een geplande onderhoudsafspraak over ' . $timeString . '.')
            ->line('Klant: ' . $this->maintenance->customer->name)
            ->line('Datum: ' . $date)
            ->line('Omschrijving: ' . $this->maintenance->description)
            ->action('Bekijk Afspraak', url('/maintenance/' . $this->maintenance->id)) // Adjust route as needed
            ->line('Zorg dat je goed voorbereid bent!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $timeString = $this->timeframe === '1_month' ? '1 maand' : '1 week';

        return [
            'message' => 'Onderhoudsafspraak over ' . $timeString . ': <strong>' . $this->maintenance->customer->name . '</strong>',
            'maintenance_id' => $this->maintenance->id,
            'type' => $this->timeframe, // Store the type (1_month or 1_week)
            'scheduled_date' => $this->maintenance->scheduled_date,
        ];
    }
}
