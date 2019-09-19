<?php

namespace App\Jobs;

use App\Mail\EmailChangedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class EmailChangedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $status;
    public $user;
    public $oldEmail;

    /**
     * Create a new job instance.
     */
    public function __construct($user, $oldEmail)
    {
        $this->user = $user;
        $this->oldEmail = $oldEmail;
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        return Mail::to($this->oldEmail)->send(new EmailChangedMail($this->user));
    }
}
