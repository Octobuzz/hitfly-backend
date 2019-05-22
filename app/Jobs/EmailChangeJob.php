<?php

namespace App\Jobs;

use App\Mail\EmailChangeMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class EmailChangeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $user;
    public $url;

    /**
     * Create a new job instance.
     */
    public function __construct($user, $email, $url)
    {
        $this->user = $user;
        $this->email = $email;
        $this->url = $url;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        return Mail::to($this->email)->send(new EmailChangeMail($this->user, $this->email, $this->url));
    }
}
