<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Http\Models\EmailsTemplates\EmailsTemplates;
use App\Jobs\SendEmail;


class MailResetPasswordToken extends Notification
{
    use Queueable;
    public $token;
   // public $email;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
       // $this->email = ['mail'];
        return ['mail'];
    }



    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $template = EmailsTemplates::where('alias_name','forgotten_password')->get();

        $body = str_replace('{link_recovery}','<a href="'.url('password/reset', $this->token).'">reset</a>',$template[0]['body_html']);

        return (new MailMessage)
            ->subject($template[0]['subject'])
            ->from($template[0]['from_addres'],$template[0]['from_name'])
            ->line($body)
            ->replyTo($template[0]['reply_to_addres']);
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
