<?php

namespace App\Jobs\Track;

use App\Models\Track;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class CreatePlayTimeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $track;

    /**
     * Create a new job instance.
     */
    public function __construct(Track $track)
    {
        $this->track = $track;
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        Artisan::call('hitfly:create_play_time', [
            'track' => $this->track->id,
        ]);
    }
}
