<?php

namespace App\Jobs;

use App\Mail\NewStatusMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class NewStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $status;
    public $user;

    /**
     * Create a new job instance.
     */
    public function __construct($status, $user)
    {
        $this->status = $status;
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        return Mail::to($this->user->email)->send(new NewStatusMail($this->user->username, $this->status));
    }
}
