<?php

namespace App\Jobs;

use App\Mail\DecreaseStatusMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DecreaseStatusJob implements ShouldQueue
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
        return \Mail::send(new DecreaseStatusMail($this->decreaseStatus, $this->oldStatus, $this->user));
    }
}
