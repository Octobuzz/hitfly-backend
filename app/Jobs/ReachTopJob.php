<?php

namespace App\Jobs;

use App\Mail\ReachTopMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ReachTopJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $track;
    public $topUrl;
    public $topCount;

    /**
     * Create a new job instance.
     */
    public function __construct($track, $topUrl, $topCount)
    {
        $this->track = $track;
        $this->topUrl = $topUrl;
        $this->topCount = $topCount;
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        return \Mail::to($this->track['user']->email)->send(new ReachTopMail($this->track, $this->topUrl, $this->topCount));
    }
}
