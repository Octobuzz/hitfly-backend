<?php

namespace App\Jobs;

use App\Mail\RegisterSocialspasswordMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class RegisterSocialsPasswordJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $pass;

    /**
     * Create a new job instance.
     */
    public function __construct($user, $pass)
    {
        $this->user = $user;
        $this->pass = $pass;
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        return Mail::to($this->user->email)->send(new RegisterSocialspasswordMail($this->user, $this->pass));
    }
}
