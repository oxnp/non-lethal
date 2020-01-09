<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $email;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        info( $this->email);

       // Log::info('Вот кое-какая полезная информация.'.$this->email."\n");
       /* Mail::raw('sdfdfsf', function($message)
        {
            $message->from(env('MAIL_FROM_ADDRESS', 'admin@gemgow.com'));
            $message->to($this->email)
                ->subject('New item on site https://gemgow.com');
        });*/
    }
}
