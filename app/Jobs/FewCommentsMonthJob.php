<?php

namespace App\Jobs;

use App\Mail\FewComments;
use App\Mail\FewCommentsMonthMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FewCommentsMonthJob implements ShouldQueue
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
        return \Mail::to($this->user->email)->send(new FewCommentsMonthMail($this->user, $this->tracks));
    }
}
