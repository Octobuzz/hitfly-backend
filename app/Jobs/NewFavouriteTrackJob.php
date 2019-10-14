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
    public $url;

    /**
     * Create a new job instance.
     */
    public function __construct($singerName, $trackName, $essence, $user, $url)
    {
        $this->essence = $essence;
        $this->singerName = $singerName;
        $this->user = $user;
        $this->trackName = $trackName;
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        return Mail::to($this->user->email)->send(new NewFavouriteTrackMail($this->singerName, $this->trackName, $this->essence, $this->user, $this->url));
    }
}
