<?php

namespace App\Notifications;

use App\Http\Models\EmailsTemplates\EmailsTemplates;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MailRegisterUser extends Notification
{
    use Queueable;
    public $user_data;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->user_data = $request;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
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


        if (isset($this->user_data['from_admin']) && $this->user_data['from_admin'] == 1){
            $template = EmailsTemplates::where('alias_name','send_email_after_add_byuer')->get();
            $body = str_replace(array('{name}','{email}','{link_change_password}'),array($this->user_data['name'],$this->user_data['email'],$this->user_data['link_change_password']),$template[0]['body_html']);

        }else{
            $template = EmailsTemplates::where('alias_name','register')->get();
            $body = str_replace(array('{name}','{username}','{password}'),array($this->user_data['name'],$this->user_data['email'],$this->user_data['password']),$template[0]['body_html']);
        }


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
