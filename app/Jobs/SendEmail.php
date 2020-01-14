<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Mail;
use mysql_xdevapi\Exception;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $email;
    protected $name;

    protected $name_from;
    protected $email_from;
    protected $email_reply;
    protected $subject;
    protected $body_html;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data_subscriber, $sender_info)
    {

        $this->email = $data_subscriber['email'];
        $this->body_html = $data_subscriber['body_html'];

        $this->name_from = $sender_info['name_from'];
        $this->email_from = $sender_info['email_from'];
        $this->email_reply = $sender_info['email_reply'];
        $this->subject = $sender_info['subject'];


    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      //  dd($this->email_from);
        try {
            Mail::send('temp_email.newsletter', ['body_html' => $this->body_html], function ($message) {
                $message->from($this->email_from);
                $message->to($this->email)
                    ->subject($this->subject);
            });
        }catch (Exception $exception){
            dd($exception);
        }

    }
}
