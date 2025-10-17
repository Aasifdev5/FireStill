<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EquipmentInspectionReminder extends Notification
{
    use Queueable;

    public $equipment;
    public $nextInspection;
    public $isCritical;

    public function __construct($equipment, $nextInspection, $isCritical = false)
    {
        $this->equipment = $equipment;
        $this->nextInspection = $nextInspection;
        $this->isCritical = $isCritical;
    }

    public function via($notifiable)
    {
        return ['mail']; // Add 'database' or 'sms' if needed
    }

    public function toMail($notifiable)
    {
        $subject = $this->isCritical ? "Critical Equipment Alert!" : "Upcoming Inspection Reminder";
        $line = $this->isCritical
            ? "Equipment {$this->equipment->code} requires urgent attention."
            : "Equipment {$this->equipment->code} has an upcoming inspection on {$this->nextInspection->format('d/m/Y')}.";

        return (new MailMessage)
            ->subject($subject)
            ->line($line)
            ->action('View Equipment', url("/equipment/{$this->equipment->id}"))
            ->line('Thank you for using our safety management system.');
    }
}
