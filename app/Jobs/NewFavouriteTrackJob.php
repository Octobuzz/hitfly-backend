<?php

namespace App\Jobs;

use App\Mail\NewFavouriteTrackMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class NewFavouriteTrackJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $singerName;
    public $essence;
    public $user;
    public $trackName;

    /**
     * Create a new job instance.
     */
    public function __construct($singerName, $trackName, $essence, $user)
    {
        $this->essence = $essence;
        $this->singerName = $singerName;
        $this->user = $user;
        $this->trackName = $trackName;
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        return Mail::to($this->user->email)->send(new NewFavouriteTrackMail($this->decreaseStatus, $this->trackName, $this->oldStatus, $this->user->username));
    }
}
