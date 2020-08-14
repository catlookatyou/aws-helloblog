<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Comment;

class PostReplied extends Notification implements ShouldQueue
{
    use Queueable;

    public $comment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    
    public function toDatabase($notifiable){
        return [
            'comment_id' => $this->comment->id,
            'comment_content' => $this->comment->content,
            'user_id' => $this->comment->user_id,
            'user_name' => $this->comment->user->name,
            'user_avatar' => $this->comment->user->getAvatarUrl(),
            'post_id' => $this->comment->post_id,
            'post_title' => $this->comment->post->title
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('您有新的回應!')
                    ->action('查看', url('/notifications'))
                    ->line('Thank you for using our application!');
    }

    public function routeNotificationForMail()
    {
        return $this->comment->user->email;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
