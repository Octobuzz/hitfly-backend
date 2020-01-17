<?php

namespace App\Jobs;

use App\Mail\FewComments;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FewCommentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tracks;
    public $user;

    /**
     * Уведомление об отсутствии отзывов.
     *
     * @param $user
     * @param $tracks
     */
    public function __construct($user, $tracks)
    {
        $this->user = $user;
        $this->tracks = $tracks;
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        return \Mail::send(new FewComments($this->user, $this->tracks));
    }
}
