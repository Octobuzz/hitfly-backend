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
    public $track, $topUrl, $eventsList, $topCount;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($track, $topUrl, $eventsList, $topCount)
    {
        $this->track = $track;
        $this->topUrl = $topUrl;
        $this->eventsList = $eventsList;
        $this->topCount = $topCount;
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        return \Mail::to($this->track['user']->email)->send(new ReachTopMail($this->track, $this->topUrl, $this->eventsList, $this->topCount));
    }
}
