<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Contracts\PostContract;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PostPublicationFailed extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var \App\Contracts\PostContract
     */
    protected $post;

    /**
     * Create a new notification instance.
     *
     * @param \App\Contracts\PostContract $post
     */
    public function __construct(PostContract $post)
    {
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }
}
