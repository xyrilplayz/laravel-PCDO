<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LoanOverdueNotification extends Notification
{
    use Queueable;

    protected $loan;

    public function __construct($loan)
    {
        $this->loan = $loan;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Find the earliest overdue payment schedule
        $dueSchedule = $this->loan->paymentSchedules()
            ->whereDate('due_date', '<', now())
            ->orderBy('due_date', 'asc')
            ->first();

        $dueDateText = $dueSchedule
            ? $dueSchedule->due_date->format('F d, Y')
            : 'No overdue schedule found';

        return (new MailMessage)
            ->subject('Loan Payment Overdue')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your loan of ₱' . number_format($this->loan->amount, 2) . ' is overdue.')
            ->line('Due Date: ' . $dueDateText)
            ->action('View Your Loan', url('/loans/' . $this->loan->id))
            ->line('Please settle your payment as soon as possible to avoid penalties.');
    }

}
