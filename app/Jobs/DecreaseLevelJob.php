<?php

namespace App\Jobs;

use App\Mail\DecreaseLevelMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class DecreaseLevelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $decreaseStatus;
    public $oldStatus;
    public $user;

    /**
     * Create a new job instance.
     */
    public function __construct($decreaseStatus, $oldStatus, $user)
    {
        $this->oldStatus = $oldStatus;
        $this->decreaseStatus = $decreaseStatus;
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        return Mail::to($this->user->email)->send(new DecreaseLevelMail($this->decreaseStatus, $this->oldStatus, $this->user->username));
    }
}
